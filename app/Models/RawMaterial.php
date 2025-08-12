<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    public $display_name = "raw materials";

    protected $guarded = [];

    public function materialGroup(){
        return $this->belongsTo(MaterialGroup::class,'material_group_id','id');
    }
}
