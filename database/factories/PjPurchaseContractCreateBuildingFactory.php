<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\PjPurchaseContractCreateBuilding;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( PjPurchaseContractCreateBuilding::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create();
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->pj_purchase_contract_create_id = 1;
    $result->pj_lot_building_a_id = 1;
    $result->house_number = '●番●';
    $result->building_number = $faker->buildingNumber();
    $result->building_parcel = '●●市●●５丁目';
    $result->building_address = $faker->streetAddress;
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( PjPurchaseContractCreateBuilding::class, 'init', function(){
	// ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->pj_purchase_contract_create_id = null;
    $result->pj_lot_building_a_id = null;
    // ----------------------------------------------------------------------
    $result->house_number = null;
    $result->building_number = null;
    $result->building_parcel = null;
    $result->building_address = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
	return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
