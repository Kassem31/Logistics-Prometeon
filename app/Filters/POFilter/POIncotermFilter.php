<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;

class POIncotermFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereIn('incoterm_id',$value);
    }
}
