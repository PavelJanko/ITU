<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'owner_id' => $faker->numberBetween(1, \App\User::count()),
        'parent_id' => rand(0, 1) ? $faker->numberBetween(1, \App\Folder::count()) : NULL,
        'name' => $faker->sentence(rand(3, 6)),
        'suffix' => $faker->randomElement($array = array ('txt','docx','xlsx', 'odt')),
        'abstract' => $faker->realText(1000),
    ];
});
