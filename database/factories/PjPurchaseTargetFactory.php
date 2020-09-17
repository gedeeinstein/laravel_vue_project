<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjPurchaseTarget;
use Faker\Generator as Faker;

$factory->define(PjPurchaseTarget::class, function (Faker $faker) {

    $money = collect([ 1000000, 2000000, 3500000, 6500000, 9800000 ]);

    return [
        'pj_purchase_id' => 0,
        'purchase_price' => $money->random(),
        'purchase_deposit' => 500000,
        'purchase_not_create_documents' => 0,
    ];
});


$factory->state( PjPurchaseTarget::class, 'init', function( Faker $faker ){
    return [
        'pj_purchase_id' => null,
        'purchase_price' => null,
        'purchase_deposit' => null,
        'purchase_not_create_documents' => null,
    ];
});
