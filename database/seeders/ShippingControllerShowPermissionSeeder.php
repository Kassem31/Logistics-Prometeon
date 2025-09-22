<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingControllerShowPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create only the 'show' permission for ShippingBasic (which is used by ShippingController)
        $permission = [
            'name' => 'ShippingBasic-show',
            'display_name' => 'Show Inbound',
            'description' => 'View specific inbound shipping details'
        ];

        // Check if permission already exists
        $existingPermission = DB::table('permissions')
            ->where('name', $permission['name'])
            ->first();

        if (!$existingPermission) {
            DB::table('permissions')->insert($permission);
            $this->command->info('Created permission: ' . $permission['name']);
        } else {
            $this->command->info('Permission already exists: ' . $permission['name']);
        }
    }
}