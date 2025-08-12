<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;

class PONoFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('po_number','like',"%{$value}%");
    }
}
