<?php

namespace App\Filters\Inbound;

use App\Filters\AbstractFilter;

class InboundIndexFilter extends AbstractFilter{
    protected $filters = [
        'inbound'=>InboundNoFilter::class,
        'po'=>PONoFilter::class,
        'acid'=>AcidNumberFilter::class,
        'bl_number'=>BlNumberFilter::class,
        'pic'=>PicFilter::class,
        'atsfrom'=>AtsDateFromFilter::class,
        'atsto'=>AtsDateToFilter::class,
        'atafrom'=>AtaDateFromFilter::class,
        'atato'=>AtaDateToFilter::class,
        'step'=>StepFilter::class,
        'status'=>ShippingStatusFilter::class
    ];
}
