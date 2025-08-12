<?php

namespace App\Filters\POFilter;

use App\Filters\AbstractFilter;

class POIndexFilter extends AbstractFilter{
    protected $filters = [
        'po'=>PONoFilter::class,
        'status'=>POStatusFilter::class,
        'orderdatefrom'=>POOrderDateFromFilter::class,
        'orderdateto'=>POOrderDateToFilter::class,
        'duedatefrom'=>PODueDateFromFilter::class,
        'duedateto'=>PODueDateToFilter::class,
        'pic'=>POPICFilter::class,
        'supplier'=>POSupplierFilter::class,
        'incoterm'=>POIncotermFilter::class
    ];
}
