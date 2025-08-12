<?php
namespace App\Filters\Inbound;

use App\Filters\AbstractBasicFilter;

class StepFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereIn('status',$value);
    }
}
