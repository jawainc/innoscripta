<?php

use Faker\Generator as Faker;

$factory->define(App\Invoice::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('-4 years'),
        'billNumber' => $faker->randomNumber(),
        'amount' => $faker->randomFloat(2, 100, '500'),
        'purpose' => $faker->text(5)
    ];
});
