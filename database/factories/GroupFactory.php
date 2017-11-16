<?php

use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'creator_id' => $faker->numberBetween(1, \App\User::count()),
        'name' => ucfirst($faker->word),
    ];
});
