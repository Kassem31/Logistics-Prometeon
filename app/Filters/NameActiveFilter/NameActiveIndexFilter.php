<?php

namespace App\Filters\NameActiveFilter;

use App\Filters\AbstractFilter;

class NameActiveIndexFilter extends AbstractFilter{
    protected $filters = [
        'name'=>NameFilter::class,
        'status'=>ActiveFilter::class
    ];
}
