<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;

class POSupplierFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereIn('supplier_id',$value);
    }
}
