<?php

namespace App\Models;

use App\Models\ShippingBasic;
use App\Traits\HasFilter;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class Inbound extends ShippingBasic
{
    use HasFilter;
    public $has_permission=false;
    protected $guarded = [];
    // protected $dates = ['order_date','due_date'];
    public $steps = [
        'inbound'=>1,
        'booking'=>2,
        'shipping'=>3,
        'document'=>4,
        'clearance'=>5,
        'delivery'=>6,
        'bank'=>7,
        'complete'=>8
    ];

    public function details(){
        return $this->hasMany(InboundDetails::class,'inbound_id','id')->whereNull('deleted_at');
    }
    public function po_header(){
        return $this->belongsTo(POHeader::class,'po_header_id','id');
    }

    public function booking(){
        return $this->hasOne(ShippingBooking::class,'shipping_id','id')->withDefault();
    }

    public function document(){
        return $this->hasOne(ShippingDocument::class,'shipping_id','id')->withDefault();
    }

    public function shipping(){
        return $this->hasOne(ShippingBasicInfo::class,'inbound_id','id')->withDefault();
    }
    public function containers(){
        return $this->hasMany(InboundContainer::class,'inbound_id','id');
    }
    public function clearance(){
        return $this->hasOne(ShippingClearance::class,'shipping_id','id')->withDefault();
    }

    public function delivery(){
        return $this->hasOne(ShippingDelivery::class,'shipping_id','id')->withDefault();
    }

    public function getClearanceDaysAttribute(){
        if(is_null($this->booking->ata)){
            return null;
        }
        $ata = Carbon::createFromFormat('d/m/Y',$this->booking->ata)->startOfDay();
        if($this->delivery->atco_date){
           $atco = Carbon::createFromFormat('d/m/Y',$this->delivery->atco_date)->startOfDay();
           return (int) $ata->diffInDays($atco);
        }
        return (int) Carbon::now()->startOfDay()->diffInDays($ata);
    }
    
    public function next(){
        return $this->booking();
    }

    public function canGoShipping(){
        // Can go to shipping after booking is complete
        $booking = $this->booking;
        if(!$booking || !$booking->id) return false;
        
        // Only check essential booking fields, allow nullable fields to be null
        // For shipping step, we just need the booking record to exist
        // The dates (ets, eta, ats, ata) can be filled later
        return true;
    }

    public function canGoBooking(){
        // Can go to booking after inbound details are complete
        if(is_null($this->po_header_id)){return false;}
        return $this->details()->count() > 0;
    }

    public function canGoDocumentCycle(){
        // Can go to document cycle after shipping is complete
        $shipping = $this->shipping;
        if(!$shipping || !$shipping->exists) return false;
        
        // Check if at least basic shipping info is present
        // We'll be more lenient here to allow natural progression
        $basicRequiredFields = ['origin_country_id', 'loading_port_id', 'inco_term_id'];
        
        foreach($basicRequiredFields as $field) {
            if(is_null($shipping->$field) || $shipping->$field === '') {
                return false;
            }
        }
        
        // Don't require containers or all shipping details to be complete
        // Users can fill these progressively within the shipping step
        return true;
    }

    public function canGoClearance(){
        // Can go to clearance after document cycle is complete
        // First ensure shipping step is complete
        if(!$this->canGoDocumentCycle()) {
            return false;
        }
        
        // Check that ALL document cycle fields are filled
        $document = $this->document;
        if(!$document || !$document->exists) {
            return false;
        }
        
        // All 9 document cycle fields must be filled before progressing to clearance
        $requiredDocumentFields = [
            'invoice_copy',
            'purchase_confirmation',
            'original_invoice',
            'stamped_invoice',
            'copy_docs',
            'original_docs',
            'copy_docs_broker',
            'original_docs_broker',
            'stamped_invoice_broker'
        ];
        
        foreach($requiredDocumentFields as $field) {
            if(is_null($document->$field) || $document->$field === '') {
                return false;
            }
        }
        
        return true;
    }

    public function canGoBank(){
        // Can go to bank after delivery is complete
        // First ensure clearance step is complete
        if(!$this->canGoDelivery()) {
            return false;
        }
        
        $delivery = $this->delivery;
        if(!$delivery || !$delivery->exists) {
            return false;
        }
        
        // All required delivery fields must be filled before progressing to bank
        // Note field is optional
        $requiredDeliveryFields = [
            'atco_date',
            'sap_date',
            'bwh_date'
        ];
        
        foreach($requiredDeliveryFields as $field) {
            if(is_null($delivery->$field) || $delivery->$field === '') {
                return false;
            }
        }
        
        return true;
    }

    public function canGoDelivery(){
        // Can go to delivery after clearance is complete
        $clearance = $this->clearance;
        if(!$clearance || !$clearance->id) return false;
        
        // Check only essential clearance fields instead of all fields
        // Basic clearance progression needs: registeration_date, inspection_date
        $essentialFields = ['registeration_date', 'inspection_date'];
        
        foreach($essentialFields as $field) {
            if(is_null($clearance->$field) || $clearance->$field === '') {
                return false;
            }
        }
        
        return true;
    }

    public function isComplete(){
        // First ensure delivery step is complete
        if(!$this->canGoBank()) {
            return false;
        }
        
        // Check that all bank fields in clearance model are filled
        $clearance = $this->clearance;
        if(!$clearance || !$clearance->exists) {
            return false;
        }
        
        // All required bank fields must be filled before marking as complete
        $requiredBankFields = [
            'bank_id',
            'invoice_no',
            'invoice_date',
            'amount',
            'invoice_currency_id',
            'bank_letter_date',
            'delivery_bank_date',
            'bank_in_date',
            'bank_out_date',
            'bank_rec_date'
        ];
        
        foreach($requiredBankFields as $field) {
            if(is_null($clearance->$field) || $clearance->$field === '') {
                return false;
            }
        }
        
        return true;
    }

    public function calcCurrentStep(){
        // Start at step 1 (Inbound Details)
        $this->currentStep = 1;
        
        // Check if we have inbound details and can move to booking
        if(!$this->canGoBooking()){
            return; // Stay on step 1 if we can't go to booking
        }
        
        // Check if booking data exists and is complete
        $booking = $this->booking;
        if(!$booking || !$booking->id || !$this->canGoShipping()) {
            // If no booking data or booking is incomplete, stay on step 2
            $this->currentStep = 2;
            return;
        }
        
        // If we reach here, booking is complete, move to step 3
        $this->currentStep = 3;
        
        if($this->canGoDocumentCycle()){
            $this->currentStep = 4;
        }else{
            return;
        }
        if($this->canGoClearance()){
            $this->currentStep = 5;
        }else{
            return;
        }
        if($this->canGoDelivery()){
            $this->currentStep = 6;
        }else{
            return;
        }
        if($this->canGoBank()){
            $this->currentStep = 7;
        }else{
            return;
        }
        if($this->isComplete()){
            $this->currentStep = 8;
        }
    }

    public function getPercent(){
        $this->calcCurrentStep();
        $totalSteps = count($this->steps);
        
        // Calculate progress more intuitively
        // Step 1 = 12.5%, Step 2 = 25%, etc.
        $percentage = ($this->currentStep / $totalSteps) * 100;
        
        // Ensure it's a proper integer and never shows as currency
        return (int) round($percentage);
    }
    
    public function getPercentDisplay(){
        return $this->getPercent() . '%';
    }

    public function updateStatus(){
        $this->status = array_search($this->currentStep,$this->steps);
        $this->save();
    }

    public function getCurrentStatus(){
        $this->calcCurrentStep();
        $this->updateStatus();
        return array_search($this->currentStep,$this->steps);
    }
    public function getStatusStyle($step){
        $stepNumber = $this->steps[$step];
        $this->calcCurrentStep();
        if($stepNumber < ($this->currentStep)){
            return "success";
        }
        if($stepNumber == $this->currentStep){
            return 'warning';
        }
        if($stepNumber > $this->currentStep){
            return 'dark';
        }
    }

    public function getShippmentStatusStyle(){
        if(!is_null($this->booking->ata)){
            return "success";
        }
        if(is_null($this->booking->ata) && !is_null($this->booking->ats)){
            return 'warning';
        }
        return 'dark';
    }

    public function getShippmentStatus(){
        if(!is_null($this->booking->ata)){
            return "Arrived";
        }
        if(is_null($this->booking->ata) && !is_null($this->booking->ats)){
            return 'In Transit';
        }
        return 'Unbooked';
    }

    public function updateDetails(Request $request){
        $this->details()->whereIn('id',$request->input('delete',[]))->update(['deleted_at'=>Carbon::now()]);
        if($request->input('inbound.po_header_id') != $this->po_header_id){
            $this->details()->update(['deleted_at'=>Carbon::now()]);
        }
        $this->update($request->input('inbound'));
        $details =  collect($request->input('detail'))->map(function($item){
            return [
                'po_detail_id'=>$item['po_detail_id'],
                'qty'=>$item['qty']
            ];
        });
        if(count($details)>0){
            $this->details()->createMany($details->all());
        }
    }

    public function updateBasic(Request $request){
        $data = $request->input('basic');
        $data['shipping_line_id'] != '0' ? $data['other_shipping_line'] = null : $data['other_shipping_line'];
        $this->fill($data);
        $this->update($data);
    }

    public function updateBooking(Request $request){
        $data = $request->input('book');
        if(is_null($data)){return;}
        $book = $this->booking;
        if(is_null($book->id)){
            $book = $this->booking()->create($data);
        }else{
            $book->fill($data);
            $book->update($data);
        }
    }

    public function updateShipping(Request $request){
        $containers = collect($request->input('container'),[])->filter(function($item){
            return !is_null($item['container_no']) || !is_null($item['container_size_id']) || !is_null($item['load_type_id']);
        });
        $container_edit = collect($request->input('container_edit'),[])->filter(function($item){
            return !is_null($item['container_no']) || !is_null($item['container_size_id']) || !is_null($item['load_type_id']);
        });

        $this->containers()->createMany($containers);
        $container_edit->each(function($item,$key){
            $this->containers()->where('id',$key)->update($item);
        });
        $this->containers()->whereIn('id',$request->input('container_delete',[]))->delete();
        $data = $request->input('shipping');
        if(!is_null($data)){
            $data['shipping_line_id'] != '0' ? $data['other_shipping_line'] = null : $data['other_shipping_line'];
        }
        if(is_null($data))return;
        $this->shipping->fill($data);
        $this->shipping->save();
    }

    public function updateDocument(Request $request){
        $data = $request->input('document');
        if(is_null($data)){return;}
        $document = $this->document;
        if(is_null($document->id)){
            $document = $this->document()->create($data);
        }else{
            $document->fill($data);
            $document->update($data);
        }
    }

    public function updateClearance(Request $request){
        $data = $request->input('clear');
        if(is_null($data)){return;}
        $clearance = $this->clearance;
        $clearance->fill($data);
        $clearance->save();
    }

    public function updateBank(Request $request){
        $data = $request->input('bank');
        if(is_null($data)){return;}
        $clearance = $this->clearance;
        $clearance->fill($data);
        $clearance->save();
    }

    public function updateDelivery(Request $request){
        $data = $request->input('deliver');
        if(is_null($data)){return;}
        $delivery = $this->delivery;
        if(is_null($delivery->id)){
            $delivery = $this->delivery()->create($data);
        }else{
            $delivery->fill($data);
            $delivery->update($data);
        }
    }
}
