<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Role::class, 'fake_roles',function (Faker $faker) {
    return [
      'name' => $faker->unique()->name,
      'display_name' => Str::random(5),
    ];
});
