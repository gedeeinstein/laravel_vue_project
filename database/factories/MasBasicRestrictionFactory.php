<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MasBasicRestriction;
use Faker\Generator as Faker;

$factory->define(MasBasicRestriction::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(MasBasicRestriction::class, 'init', [
    'id' => null,
    // 'mas_basic_project_id' => null,
    'mas_basic_id' => null,
    'restriction_id' => null,
    'restriction_note' => null,
]);
