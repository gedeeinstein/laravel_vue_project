<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\MasFinanceExpense::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;

    $data->decided = $faker->numberBetween(2000, 5000);
    $data->payperiod = $faker->dateTimeThisYear($max = 'now', $timezone = null);
    $data->payee = $faker->numberBetween(1, 20);
    $data->note = 'テスト';
    $data->paid = $faker->numberBetween(2000, 5000);
    $data->date = $faker->dateTimeThisYear($max = 'now', $timezone = null);
    $data->bank = $faker->numberBetween(1, 10);
    $data->taxfree = 1;
    $data->status = $faker->numberBetween(1, 3);

    $data->category_index = '';
    $data->display_name = '';
    $data->mas_finance_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
