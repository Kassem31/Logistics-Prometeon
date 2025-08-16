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
        $ata = Carbon::createFromFormat('d/m/Y',$this->booking->ata);
        if($this->delivery->atco_date){
           $atco =  Carbon::createFromFormat('d/m/Y',$this->delivery->atco_date);
           return $atco->diffInDays($ata);
        }
        return Carbon::now()->diffInDays($ata);
    }

    public function next(){
        return $this->booking();
    }

    public function canGoShipping(){
        // Can go to shipping after booking is complete
        $booking = $this->booking;
        if(!$booking || !$booking->id) return false;
        
        // Check if basic booking fields are completed
        $attributes = collect($booking->getAttributes())->except(['id', 'created_at', 'updated_at', 'deleted_at']);
        return !$attributes->contains(function($value){
            return is_null($value);
        });
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
        
        // Check if containers are properly filled
        $containers = $this->containers;
        if($containers->count() <= 0) return false;
        
        $incompleteContainers = $containers->filter(function($item){
            return is_null($item['container_no']) || is_null($item['container_size_id']) || is_null($item['load_type_id']);
        })->count();
        
        if($incompleteContainers > 0) return false;
        
        // Check basic shipping fields that are always required
        $basicRequiredFields = ['origin_country_id', 'loading_port_id', 'inco_term_id', 'shipping_line_id', 'vessel_name', 'bl_number'];
        
        foreach($basicRequiredFields as $field) {
            if(is_null($shipping->$field) || $shipping->$field === '') {
                return false;
            }
        }
        
        // Check if incoterm affects required fields
        $incoTerm = optional($shipping)->incoTerm;
        $incoPrefix = optional($incoTerm)->prefix ?? '';
        $hideForwarder = in_array(strtolower($incoPrefix), ['cif', 'cfr', 'ddu']);
        $hideInsurance = strtolower($incoPrefix) == 'cif';
        
        // Check conditional forwarder fields
        if(!$hideForwarder) {
            $forwarderFields = ['inco_forwarder_id', 'currency_id', 'rate'];
            foreach($forwarderFields as $field) {
                if(is_null($shipping->$field) || $shipping->$field === '') {
                    return false;
                }
            }
        }
        
        // Check conditional insurance fields
        if(!$hideInsurance) {
            $insuranceFields = ['insurance_company_id', 'insurance_date', 'insurance_cert_no'];
            foreach($insuranceFields as $field) {
                if(is_null($shipping->$field) || $shipping->$field === '') {
                    return false;
                }
            }
        }
        
        // Check if Other shipping line is selected and other_shipping_line is required
        if($shipping->shipping_line_id == '0' || $shipping->shipping_line_id === 0) {
            if(is_null($shipping->other_shipping_line) || $shipping->other_shipping_line === '') {
                return false;
            }
        }
        
        return true;
    }

    public function canGoClearance(){
        // Can go to clearance after document cycle is complete
        return $this->canGoDocumentCycle();
    }

    public function canGoBank(){
        // Can go to bank after delivery is complete
        $delivery = $this->delivery;
        if(!$delivery) return false;
        
        // Check if basic delivery fields are completed
        $attributes = collect($delivery->getAttributes())->except(['id', 'created_at', 'updated_at', 'deleted_at']);
        return !$attributes->contains(function($value){
            return is_null($value);
        });
    }

    public function canGoDelivery(){
        // Can go to delivery after clearance is complete
        $clearance = $this->clearance;
        if(!$clearance) return false;
        
        // Check if basic clearance fields are completed
        $attributes = collect($clearance->getAttributes())->except(['id', 'created_at', 'updated_at', 'deleted_at']);
        return !$attributes->contains(function($value){
            return is_null($value);
        });
    }

    public function isComplete(){
        if(is_null($this->delivery->id)){
            return false;
        }
        $attributes = collect($this->delivery->getAttributes());
        $attributes = $attributes->except('bwh_date');
        return !$attributes->contains(function($value){
            return is_null($value);
        });
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
        return floor((($this->currentStep - 1) / $this->totalSteps) * 100);
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
        return 'Unknown';
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
        $clearance->save($data);
    }

    public function updateBank(Request $request){
        $data = $request->input('bank');
        if(is_null($data)){return;}
        $clearance = $this->clearance;
        $clearance->fill($data);
        $clearance->save($data);
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
