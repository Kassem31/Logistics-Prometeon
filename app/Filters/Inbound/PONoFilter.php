<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class PONoFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereHas('po_header',function($q) use($value){
            $q->where('po_number','like',"%{$value}%");
        });
    }
}
