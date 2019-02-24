<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'customerNumber' => $faker->randomNumber(),
        'cost' => $faker->randomFloat(2, 3000),
        'street' => $faker->address
    ];
});
