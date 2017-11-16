<?php

use Faker\Generator as Faker;

$factory->define(App\Folder::class, function (Faker $faker) {
    return [
        'owner_id' => $faker->numberBetween(1, \App\User::count()),
        'name' => $faker->sentence(rand(1, 4)),
    ];
});
