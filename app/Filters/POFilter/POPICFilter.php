<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;

class POPICFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereIn('person_in_charge_id',$value);
    }
}
