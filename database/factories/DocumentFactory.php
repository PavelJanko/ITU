<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    $documentOwner = App\User::find($faker->numberBetween(1, \App\User::count()));

    return [
        'owner_id' => $documentOwner,
        'parent_id' => $documentOwner->folders->count() ? $documentOwner->folders->pluck('id')->random() : NULL,
        'name' => $faker->sentence(rand(3, 6)),
        'suffix' => $faker->randomElement($array = array ('txt','docx','xlsx', 'odt')),
        'abstract' => $faker->realText(1000),
    ];
});
