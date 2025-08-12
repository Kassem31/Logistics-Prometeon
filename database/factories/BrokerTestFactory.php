<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\Broker::class, 'fake_brokers',function (Faker $faker) {
    return [
      'name' => $faker->name,
      'phone' => $faker->phoneNumber,
    ];
});
