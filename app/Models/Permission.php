<?php

namespace App\Models;

use Laratrust\Models\Permission as LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $has_permission = false;

    protected $fillable = ['name', 'display_name', 'description'];
}
