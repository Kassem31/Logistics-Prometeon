<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\POHeader::class, 'fake_pos',function (Faker $faker) {
    return [
      'supplier_id' => 1000,
      'person_in_charge_id' => 1000,
      'status' => 'Open',
      'po_number' => $faker->unique()->name,
      'order_date' =>  \Carbon\Carbon::now(),
      'due_date' =>  \Carbon\Carbon::now(),
      'created_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now(),

    ];
});
