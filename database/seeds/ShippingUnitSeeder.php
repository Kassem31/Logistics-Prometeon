<?php

use Illuminate\Database\Seeder;

class ShippingUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shipping_units')->truncate();
        $data = [
            ['name'=>'Palets','prefix'=>'PLT'],
            ['name'=>'Jumbo Bags','prefix'=>'JBG'],
            ['name'=>'Tons','prefix'=>'ton'],
        ];
        DB::table('shipping_units')->insert($data);

    }
}
