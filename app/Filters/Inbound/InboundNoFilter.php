<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class InboundNoFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('inbound_no','like',"%{$value}%");
    }
}
