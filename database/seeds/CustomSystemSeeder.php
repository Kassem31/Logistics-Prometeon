<?php

use App\Models\CustomSystem;
use Illuminate\Database\Seeder;

class CustomSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomSystem::truncate();
        CustomSystem::create([
            'name'=>'Drawback (DB)'
        ]);
        CustomSystem::create([
            'name'=>'Final'
        ]);
        CustomSystem::create([
            'name'=>'Transit'
        ]);
        CustomSystem::create([
            'name'=>'Temp'
        ]);
    }
}
