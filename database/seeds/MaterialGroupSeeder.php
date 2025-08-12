<?php

use Illuminate\Database\Seeder;

class MaterialGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('material_groups')->truncate();
        $data = [
            ['name'=>'Natural Rubber'],
            ['name'=>'Synthetic Rubber'],
            ['name'=>'Steel Cord'],
            ['name'=>'Carbon black'],
            ['name'=>'Chemicals'],
            ['name'=>'Bladders'],
            ['name'=>'Tubes and Flaps'],
            ['name'=>'Moulde'],
            ['name'=>'Spare parts'],
            ['name'=>'Auxiliaries'],
            ['name'=>'Sample'],
        ];
        DB::table('material_groups')->insert($data);
    }
}
