<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\IncoTerm::class, 'fake_inco_terms',function (Faker $faker) {
    return [
      'name' => $faker->name,
      'prefix' => $faker->name,
      'is_active' => 1,
    ];
});
