<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\ShippingBasicInfo::class, 'fake_shipping_basics',function (Faker $faker) {
    return [
      'raw_material_id' => 1000,
      'person_in_charge_id' => 9999,
      'supplier_id' => 1000,
      'po_number' =>$faker->unique()->name,
      'order_date' =>  \Carbon\Carbon::now(),
      'due_date' =>  \Carbon\Carbon::now(),
      'created_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now(),

    ];
});
