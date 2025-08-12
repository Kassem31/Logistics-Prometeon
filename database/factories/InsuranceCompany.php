<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\InsuranceCompany::class, 'fake_insurance_company',function (Faker $faker) {
    return [
      'name' => $faker->unique()->name,
      'is_active' => 1
    ];
});
