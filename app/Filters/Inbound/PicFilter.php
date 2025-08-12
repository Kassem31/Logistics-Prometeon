<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class PicFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereHas('po_header',function($q) use($value){
            $q->whereIn('person_in_charge_id',$value);
        });
    }
}
