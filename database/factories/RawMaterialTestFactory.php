<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\RawMaterial::class, 'fake_raw_materials',function (Faker $faker) {
    return [
      'name' => $faker->name,
      'sap_code' => $faker->unique()->name,
      'material_group_id' => 2,
    ];
});
