<?php

namespace App\Models;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
    use HasFilter;
    public $display_name = "Insurance Company";

    protected $guarded = [];
}
