<?php

// Test script to verify menu filtering logic
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Find a user with some permissions
$user = User::with('roles.permissions')->first();

if ($user) {
    echo "Testing with user: " . $user->name . "\n";
    echo "Is super admin: " . ($user->is_super_admin ? 'Yes' : 'No') . "\n";

    // Set the authenticated user
    Auth::setUser($user);

    // Test the hasPermission method
    if (method_exists($user, 'hasPermission')) {
        echo "hasPermission method exists\n";

        // Test with permission ID 10 (Bank-List)
        $hasPermission = $user->hasPermission(10);
        echo "User has permission ID 10: " . ($hasPermission ? 'Yes' : 'No') . "\n";

        // Show user's permissions
        $permissions = $user->roles()->with('permissions')->get()->pluck('permissions')->flatten();
        echo "User permissions: " . $permissions->pluck('name')->implode(', ') . "\n";
    } else {
        echo "hasPermission method not found\n";
    }

    // Test menu generation
    try {
        $menus = Menu::generate();
        echo "Menu generation successful. Groups: " . count($menus) . "\n";

        foreach ($menus as $groupName => $items) {
            echo "Group '$groupName': " . count($items) . " items\n";
        }
    } catch (Exception $e) {
        echo "Menu generation failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "No users found\n";
}
