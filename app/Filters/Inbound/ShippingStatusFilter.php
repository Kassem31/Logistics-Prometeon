<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class ShippingStatusFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        $this->builder->whereHas('booking',function($booking)use($value){
            $booking->where(function($q) use ($value){
                if(in_array('Arrived',$value)){
                    $q->orWhereNotNull('ata');
                }
                if(in_array('In-Transit',$value)){
                    $q->orWhere(function($a){
                        $a->whereNotNull('ats')->whereNull('ata');
                    });
                }
                if(in_array('Unknown',$value)){
                    $q->orWhere(function($a){
                        $a->whereNull('ats')->whereNull('ata');
                    });
                }
            });
        });
        return $this->builder;
    }
}
