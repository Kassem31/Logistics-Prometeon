<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;

class POStatusFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('status',$value);
    }
}
