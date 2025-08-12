<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\Port::class, 'fake_ports',function (Faker $faker) {
    return [
      'name' => $faker->unique()->name,
      'country_id' => 1
    ];
});
