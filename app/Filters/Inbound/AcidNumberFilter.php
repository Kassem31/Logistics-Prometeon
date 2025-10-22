<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class AcidNumberFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('acid_number','like',"%{$value}%");
    }
}