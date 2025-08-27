<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        $this->call(TestUserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(PortSeeder::class);
        $this->call(MaterialGroupSeeder::class);
        $this->call(ShippingUnitSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(IncoTermSeeder::class);
        $this->call(LoadTypeSeeder::class);
        $this->call(CustomSystemSeeder::class);
    }
}
