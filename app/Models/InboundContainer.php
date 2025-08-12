<?php

namespace App\Models;

use App\Models\ShippingBasic;
use Illuminate\Database\Eloquent\Model;

class InboundContainer extends ShippingBasic
{
    protected $guarded = [];
    public $has_permission=false;
}
