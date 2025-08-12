<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\Supplier::class, 'fake_suppliers',function (Faker $faker) {
    return [
      'name' => $faker->name,
      'sap_code' => $faker->unique()->name,
    ];
});
