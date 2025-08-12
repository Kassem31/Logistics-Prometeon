<?php

use Illuminate\Database\Seeder;

class IncoTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inco_terms')->truncate();
        $data = [
            ['name'=>'Cost and Freight','prefix'=>'CFR','is_active'=>1],
            ['name'=>'Costs, Insurance, and freight','prefix'=>'CIF','is_active'=>1],
            ['name'=>'Carriage paid to','prefix'=>'CPT','is_active'=>1],
            ['name'=>'Delivered at Frontier','prefix'=>'DAF','is_active'=>1],
            ['name'=>'Delivered at Place','prefix'=>'DAP','is_active'=>1],
            ['name'=>'Delivered Ex Ship-(named port of destination)','prefix'=>'DES','is_active'=>1],
            ['name'=>'Delivered Ex Quay (duty paid)','prefix'=>'DEQ','is_active'=>1],
            ['name'=>'Delivered duty unpaid','prefix'=>'DDU','is_active'=>1],
            ['name'=>'Ex Works','prefix'=>'EXW','is_active'=>1],
            ['name'=>'Free Alongside Ship','prefix'=>'FAS','is_active'=>1],
            ['name'=>'Free Carrier','prefix'=>'FCA','is_active'=>1],
            ['name'=>'Free on board','prefix'=>'FOB','is_active'=>1],


        ];
        DB::table('inco_terms')->insert($data);
    }
}
