<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\MasSection::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;
    static $number = 1;

    $data->section_number       = "i-$number";
    $data->port_section_number  = '[HA]0520(i/ii)[K]5/7';
    $data->size                 = $faker->randomFloat(4, 1, 50);
    $data->price_budget         = $faker->numberBetween(5000, 25000);
    $data->condition_build      = $faker->numberBetween(1, 3);
    $data->condition_build_sub  = $faker->numerify('Sub ###');
    $data->profit_budget_rate   = $faker->numberBetween(2000, 5000);
    $data->profit_decided       = $faker->randomFloat(1, 1, 99);
    $data->book_price_type      = $faker->numberBetween(1, 3);
    $data->book_price           = $faker->randomFloat( 2, 2000, 5000 );
    $data->plan_status          = $faker->numberBetween(1, 2);
    $data->name                 = 'テスト';
    $data->size_total           = $faker->randomFloat(4, 50, 100);
    $data->price_budget_total   = $faker->numberBetween(5000, 15000);
    $data->profit_budget_total  = $faker->numberBetween(5000, 15000);
    $data->profit_decided_total = $faker->numberBetween(5000, 15000);
    $data->book_price_total     = $faker->numberBetween( 5000, $data->price_budget );

    $data->mas_section_plan_id = 1;
    $data->project_id = 1;

    $number++;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
