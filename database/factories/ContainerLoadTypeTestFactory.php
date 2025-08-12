<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\ContainerLoadType::class, 'fake_container_load_types',function (Faker $faker) {
    return [
      'name' => $faker->name,
      'prefix' => $faker->name,
    ];
});
