<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MaterialGroup extends Model
{
    public $display_name = "material groups";

    protected $guarded = [];

    public function rawMaterials(){
        return $this->hasMany(RawMaterial::class,'material_group_id','id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'material_group_user','material_group_id','user_id');
    }
}
