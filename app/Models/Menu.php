<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    public $has_permission = false;

    public static function generate()
    {
        $user = Auth::user();

        // Temporary solution: show all menus for super admin, limited for others
        $menus = Menu::orderBy('l1_order')
            ->orderBy('l2_order');

        // For now, if user is not super admin, we'll just show all menus
        // This should be replaced with proper permission checking later
        if (!$user || !$user->is_super_admin) {
            // You can add specific permission filtering here when you implement
            // a permissions system like spatie/laravel-permission
        }

        $menus = $menus->get();
        $menus = $menus->groupBy('level_1');

        // filter menu with permission_id
        if ($user && !$user->is_super_admin) {
            $menus = $menus->map(function ($group) use ($user) {
                return $group->filter(function ($menu) use ($user) {
                    // Check if user has the permission
                    return $user->hasPermissionID($menu->permission_id);
                });
            })->filter(function ($group) {
                // Remove empty groups
                return $group->isNotEmpty();
            });
        }

        return $menus;
    }
}
