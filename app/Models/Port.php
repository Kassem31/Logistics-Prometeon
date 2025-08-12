<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    public $display_name = "ports";

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
