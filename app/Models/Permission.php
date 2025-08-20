<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $has_permission = false;

    protected $fillable = ['name', 'display_name', 'description'];
}
