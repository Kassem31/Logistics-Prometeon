<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class BlNumberFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereHas('shipping', function($q) use ($value) {
            return $q->where('bl_number','like',"%{$value}%");
        });
    }
}