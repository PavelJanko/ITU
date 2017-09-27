<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(rand(3, 6)),
        'abstract' => $faker->realText(1000),
    ];
});
