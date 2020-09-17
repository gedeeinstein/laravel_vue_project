<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MasSectionPayoff;
use Faker\Generator as Faker;

$factory->define(MasSectionPayoff::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(MasSectionPayoff::class, 'init', [
    'id' => null,
    'mas_section_id' => null,
    'company_id' => null,
    'profit_rate' => null,
    'profit_rate_total' => 0,
    'profit' => 0,
    'adjust' => 0,
    'adjusted' => 0,
]);
