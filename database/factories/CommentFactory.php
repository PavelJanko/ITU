<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'author_id' => $faker->numberBetween(1, \App\User::count()),
        'document_id' => $faker->numberBetween(1, \App\Document::count()),
        'body' => $faker->realText(),
    ];
});
