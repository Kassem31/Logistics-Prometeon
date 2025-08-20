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
                    // If menu has no permission_id, allow it
                    if (empty($menu->permission_id)) {
                        return true;
                    }
                    // Get permission by ID and check by name using Laratrust
                    $permission = \App\Models\Permission::find($menu->permission_id);
                    if (!$permission) {
                        return false;
                    }
                    return $user->hasPermission($permission->name);
                });
            })->filter(function ($group) {
                // Remove empty groups
                return $group->isNotEmpty();
            });
        }

        return $menus;
    }
}
