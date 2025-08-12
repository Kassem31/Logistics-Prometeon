<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->defineAs(User::class, 'fake_users',function (Faker $faker) {
    return [
      'full_name' => $faker->name,
      'name' => $faker->unique()->name,
      'password' => Hash::make(123456),
      'is_active' => 1
    ];
});
