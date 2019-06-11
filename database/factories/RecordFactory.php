<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Record;
use Faker\Generator as Faker;

$factory->define(Record::class, function (Faker $faker) {
    return [
        'subscriber' => $faker->unique()->name,
        'phone' => $faker->phoneNumber,
    ];
});
