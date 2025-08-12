<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    public static function generate(){
        $user = Auth::user();
        
        // Temporary solution: show all menus for super admin, limited for others
        $menus = Menu::orderBy('l1_order')
        ->orderBy('l2_order');
        
        // For now, if user is not super admin, we'll just show all menus
        // This should be replaced with proper permission checking later
        if(!$user || !$user->is_super_admin){
            // You can add specific permission filtering here when you implement
            // a permissions system like spatie/laravel-permission
        }
        
        $menus = $menus->get();
        $menus = $menus->groupBy('level_1');
        return $menus;
    }
}
