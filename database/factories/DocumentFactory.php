<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    $documentOwner = App\User::find($faker->numberBetween(1, \App\User::count()));

    return [
        'owner_id' => $documentOwner,
        'parent_id' => $documentOwner->folders->count() ? $documentOwner->folders->pluck('id')->random() : NULL,
        'name' => $faker->sentence(rand(3, 6)),
        'extension' => $faker->fileExtension,
        'abstract' => $faker->realText(1000),
    ];
});
