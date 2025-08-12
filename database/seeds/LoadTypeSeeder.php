<?php

use Illuminate\Database\Seeder;

class LoadTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('container_load_types')->truncate();
        $data = [
            ['name'=>'Full Container Loading','prefix'=>'FCL'],
            ['name'=>'Less Container Loading','prefix'=>'LCL'],

        ];
        DB::table('container_load_types')->insert($data);
    }
}
