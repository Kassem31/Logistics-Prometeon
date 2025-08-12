<?php

namespace App\Models;

use App\Models\ShippingBasic;
use App\Traits\HasFilter;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class Inbound extends ShippingBasic
{
    use HasFilter;
    public $has_permission=false;
    protected $guarded = [];
    // protected $dates = ['order_date','due_date'];
    public $steps = [
        'inbound'=>1,
        'document'=>2,
        'booking'=>3,
        'shipping'=>4,
        'clearance'=>5,
        'bank'=>6,
        'delivery'=>7,
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
        $book = $this->booking;
        return !is_null($book->ats);

    }

    public function canGoBooking(){
        $attributes = collect($this->document->getAttributes())->except('shipping_id');
        if(count($attributes) <= 0){
            return false;
        }
        return !collect($this->document->getAttributes())->except('shipping_id')->contains(function($value){
            return is_null($value);
        });
        // return !collect($this->attributes)->contains(function($value, $key){
        //     if($key == 'other_shipping_line'&& $this->attributes['shipping_line_id']!= 0 ){
        //         return is_null($this->attributes['shipping_line_id']);
        //     }
        //     return is_null($value);
        // });
    }

    public function canGoDocumentCycle(){
        if(is_null($this->po_header_id)){return false;}
        return $this->details()->count() > 0;
        if(!$this->clearance->exists) return false;
        $fields = collect($this->clearance->getAttributes());
        $basicFileds = $fields->only(['bank_id','invoice_no','invoice_date',
                    'amount','invoice_currency_id','bank_letter_date','delivery_bank_date','bank_in_date','bank_out_date','bank_rec_date']);

        $isComplete  = !$basicFileds->contains(function($value){
                        return is_null($value);
                    });

        if($isComplete){
            $custom = strtolower($this->clearance->customSystem->name);
            if($custom == 'drawback (db)'){
                return !$fields->only(['form4_issue_date','form4_rec_date','form4_number'])->contains(function($value){
                    return is_null($value);
                });
            }elseif($custom == 'final'){
                return !$fields->only(['form6_issue_date','form6_rec_date'])->contains(function($value){
                    return is_null($value);
                });
            }elseif($custom == 'transit'){
                return  !$fields->only(['transit_issue_date','transit_rec_date','transit_storage_letter'])->contains(function($value){
                    return is_null($value);
                });
            }else{
                return !$fields->only(['lg_request_date','lg_number','lg_issuance_date','lg_amount','lg_currency_id','lg_broker_receipt_date'])->contains(function($value){
                    return is_null($value);
                });
            }
        }
        return false;

    }

    public function canGoClearance(){
        if(!$this->shipping->exists) return false;
        $containers = $this->containers;
        if($containers->count() <= 0){return false;}
        $incomplete = $containers->filter(function($item){
            return is_null($item['container_no']) || is_null($item['container_size_id']) || is_null($item['load_type_id']);
        })->count();
        if($incomplete > 0){return false;}
        $shipping = collect($this->shipping->getAttributes());
        $incoTerm = optional($this->shipping)->incoTerm;
        $incoPrefix = optional($incoTerm)->prefix ?? '';
        $hideForworder = in_array(strtolower($incoPrefix),['cif','cfr','ddu']);
        $hideInsurance = strtolower($incoPrefix) == 'cif';
        $shipping = $shipping->only(['origin_country_id','loading_port_id','inco_term_id','inco_forwarder_id','currency_id',
        'shipping_line_id','rate','vessel_name','bl_number','other_shipping_line','insurance_company_id','insurance_date','insurance_cert_no']);
        if($hideForworder){
            $shipping = $shipping->except('inco_forwarder_id','currency_id','rate');
        }
        if($hideInsurance){
            $shipping = $shipping->except('insurance_company_id','insurance_date','insurance_cert_no');
        }
        $x =  !$shipping->contains(function($value,$key) use($shipping){
            if($key == 'other_shipping_line' && $shipping['shipping_line_id'] == '0'){
                return is_null($value);
            }
            if($key == 'other_shipping_line' && $shipping['shipping_line_id'] != '0'){
                return is_null($shipping['shipping_line_id']);
            }
            return is_null($value);
        });
        return $x;
    }

    public function canGoBank(){
        return !is_null($this->clearance->broker_id) && !is_null($this->clearance->custom_system_id);
    }

    public function canGoDelivery(){
        if(is_null($this->document->id)){
           return false;
        }
        if(is_null($this->booking->ata)){
            return false;
        }
        return !collect($this->document->getAttributes())->contains(function($value){
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
        $this->currentStep = 1;
        if($this->canGoDocumentCycle()){
            $this->currentStep = 2;
        }else{ return ;}
        if($this->canGoBooking()){
            $this->currentStep = 3;
        }else{return ;}
        if($this->canGoShipping()){
            $this->currentStep = 4;
        }else{return ;}
        if($this->canGoClearance()){
            $this->currentStep = 5;
        }else{return ;}
        if($this->canGoBank()){
            $this->currentStep = 6;
        }else{return ;}
        if($this->canGoDelivery()){
            $this->currentStep = 7;
        }else{return ;}
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
