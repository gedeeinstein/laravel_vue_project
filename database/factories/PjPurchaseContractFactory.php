<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjPurchaseContract;
use Faker\Generator as Faker;

$factory->define(PjPurchaseContract::class, function (Faker $faker) {
    return[
      'pj_purchase_target_id' => 1,
      'contract_building_number' => $faker->numberBetween(1,2),
      'contract_building_kind' => $faker->numberBetween(1,2),
      'contract_building_unregistered' => $faker->numberBetween(0,1),
      'contract_building_unregistered_kind' => $faker->numberBetween(1,2),
      'contract_price' => 500000,
      'contract_deposit' => 500000,
      'mediation' => $faker->numberBetween(1,2),
      'seller' => $faker->numberBetween(1,2),
      'seller_broker_company_id' => $faker->numberBetween(1,2),
      'contract_date' => $faker->dateTimeThisDecade($max = 'now', $timezone = null),
      'contract_payment_date' => $faker->dateTimeThisDecade($max = 'now', $timezone = null),
      'contract_price_building' => 500000,
      'contract_price_building_no_tax' => $faker->numberBetween(0,1),
      'contract_delivery_money' => 500000,
      'contract_delivery_date' => $faker->dateTimeThisDecade($max = 'now', $timezone = null),
      'contract_delivery_status' => $faker->numberBetween(0,2),
      'contract_delivery_bank' => $faker->numberBetween(0,2),
      'contract_delivery_note' => $faker->sentence($nbWords = 6, $variableNbWords = true),
      'contract_not_create_documents' => $faker->numberBetween(0,1),
      'contract_price_total' => 1000000,
    ];
});


$factory->state( PjPurchaseContract::class, 'init', function( Faker $faker ){
    return [
      'pj_purchase_target_id' => null,
      'contract_building_number' => null,
      'contract_building_kind' => null,
      'contract_building_unregistered' => null,
      'contract_building_unregistered_kind' => null,
      'contract_price' => null,
      'contract_deposit' => null,
      'mediation' => null,
      'seller' => null,
      'seller_broker_company_id' => null,
      'contract_date' => null,
      'contract_payment_date' => null,
      'contract_price_building' => null,
      'contract_price_building_no_tax' => null,
      'contract_delivery_money' => null,
      'contract_delivery_date' => null,
      'contract_delivery_status' => null,
      'contract_delivery_bank' => null,
      'contract_delivery_note' => null,
      'contract_not_create_documents' => null,
      'contract_price_total' => null,
    ];
});