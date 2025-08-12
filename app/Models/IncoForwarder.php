<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncoForwarder extends Model
{
    public $display_name = "inco forwarders";

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

}
