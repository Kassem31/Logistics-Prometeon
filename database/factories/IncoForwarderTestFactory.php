<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(App\Models\IncoForwarder::class, 'fake_inco_forwarders',function (Faker $faker) {
    return [
      'name' => $faker->name,
    ];
});
