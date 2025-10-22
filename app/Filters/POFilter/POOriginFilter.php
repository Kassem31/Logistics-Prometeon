<?php
namespace App\Filters\POFilter;

use App\Filters\AbstractBasicFilter;

class POOriginFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereHas('details', function($query) use ($value) {
            $query->whereIn('origin_country_id', $value);
        });
    }
}
