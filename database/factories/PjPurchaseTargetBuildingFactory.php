<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjPurchaseTargetBuilding;
use Faker\Generator as Faker;

$factory->define(PjPurchaseTargetBuilding::class, function (Faker $faker) {
    return [
        // 'pj_purchase_target_id' => $faker->unique(true)->numberBetween(1, App\Models\PjPurchaseTarget::count()),
        'pj_purchase_target_id' => 1,
        'kind' => $faker->numberBetween(1,2),
        'exist_unregistered' => $faker->numberBetween(1,2),
        'purchase_third_person_occupied' => $faker->numberBetween(1,2),
    ];
});
