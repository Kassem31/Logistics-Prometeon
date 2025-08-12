<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFilter;
    public $display_name = "banks";

    protected $guarded = [];
}
