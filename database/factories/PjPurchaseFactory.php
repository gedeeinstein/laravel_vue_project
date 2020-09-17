<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjPurchase;
use Faker\Generator as Faker;

$factory->define(PjPurchase::class, function (Faker $faker) {
    return [
        'project_id' =>  $faker->unique()->numberBetween(1, App\Models\Project::count()),
        'count' => $faker->numberBetween(1, 3),
    ];
});
