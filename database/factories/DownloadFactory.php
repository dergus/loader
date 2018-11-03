<?php
use Faker\Generator as Faker;

$factory->define(App\Models\Download::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'status' => $faker->randomElement([\App\Models\Download::STATUS_PENDING, \App\Models\Download::STATUS_COMPLETE]),
        'storage_path' => $faker->word
    ];
});