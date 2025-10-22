<?php

namespace App\Observers;

use App\Models\Inbound;
use App\Models\ShippingBasicInfo;
use App\Models\ShippingBasic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ModelObserver
{
    public function updating(ShippingBasic $shipping){
        $dirty = $shipping->getDirty();
        
        // Exclude internal wizard navigation fields from logging
        $excludedFields = ['status', 'current_step', 'updated_at'];
        $dirty = array_diff_key($dirty, array_flip($excludedFields));
        
        // If no loggable changes remain, skip logging
        if(empty($dirty)) {
            return;
        }
        
        if($shipping instanceof Inbound){
            $id = $shipping->id;
        }elseif($shipping instanceof ShippingBasicInfo){
            $id = $shipping->inbound_id;
        }
        else{
            $id = $shipping->shipping_id;
        }
        $x = collect($dirty)->map(function($item,$key)use($shipping,$id){
            return [
                'instance_id'=>$id,
                'class_name'=>$shipping->display_name,
                'field_name'=>$key,
                'field_value'=>$item,
                'user_id'=>Auth::id(),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ];
        });
        DB::table('shipping_loggers')->insert($x->all());
    }
}
