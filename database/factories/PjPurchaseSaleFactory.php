<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\PjPurchaseSale;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( PjPurchaseSale::class, function(){
	// ----------------------------------------------------------------------
	$faker = Faker::create();
	// ----------------------------------------------------------------------
	$result = new \stdClass;
	$result->project_id = 10;
	$result->company_id_organizer = 0;
	$result->organizer_realestate_explainer = 0;
	$result->project_address = $faker->streetAddress;
	$result->project_address_extra = $faker->streetAddress;
	// ----------------------------------------------------------------------
	$result->offer_route = rand( 0, 1 );
	$result->offer_date = Carbon::now()->addDays( $faker->numberBetween( 30, 90 ));
	// ----------------------------------------------------------------------
	$result->project_type = rand( 1, 3 );
	// ----------------------------------------------------------------------
	$result->registry_size = $faker->randomFloat( 2, 1, 2 );
	$result->registry_size_status = rand( 1, 2 );
	$result->survey_size = $faker->randomFloat( 2, 1, 2 );
	// ----------------------------------------------------------------------
	$result->survey_size_status = rand( 1, 3 );
	$result->project_size = $faker->randomFloat( 10, 1, 2 );
	$result->project_size_status = rand( 1, 4 );
	// ----------------------------------------------------------------------
	$result->purchase_price = '';
	// ----------------------------------------------------------------------
	$result->project_status = $faker->numberBetween( 1, 8 );
	// ----------------------------------------------------------------------
	$result->project_urbanization_area = rand( 1, 5 );
	$result->project_urbanization_area_status = rand( 1, 2 );
	$result->project_urbanization_area_sub = rand( 1, 2 );
	$result->project_urbanization_area_date = Carbon::now();
	// ----------------------------------------------------------------------
	$result->created_at = Carbon::now();
	$result->updated_at = Carbon::now();
	// ----------------------------------------------------------------------
	return (array) $result;
	// ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------

$factory->state(PjPurchaseSale::class, 'init', [
	// -------------------------------------------------------------------------
	'id' => 0,
	'project_id' => 0,
	// -------------------------------------------------------------------------
	'company_id_organizer' => 0,
	'organizer_realestate_explainer' => 0,
	// -------------------------------------------------------------------------
	'project_address' => null,
	'project_address_extra' => null,
	// -------------------------------------------------------------------------
	'offer_route' => null,
	'offer_date' => null,
	// -------------------------------------------------------------------------
	'project_type' => 0,
	// -------------------------------------------------------------------------
	'registry_size' => null,
	'registry_size_status' => null,
	// -------------------------------------------------------------------------
	'survey_size' => null,
	'survey_size_status' => null,
	// -------------------------------------------------------------------------
	'project_size' => null,
	'project_size_status' => null,
	// -------------------------------------------------------------------------
	'purchase_price' => null,
	'project_status' => 6,
	// -------------------------------------------------------------------------
	'project_urbanization_area' => null,
	'project_urbanization_area_status' => null,
	'project_urbanization_area_sub' => null,
	'project_urbanization_area_date' => null,
	// -------------------------------------------------------------------------
]);
