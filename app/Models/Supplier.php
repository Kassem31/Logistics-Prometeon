<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $display_name = "suppliers";

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
