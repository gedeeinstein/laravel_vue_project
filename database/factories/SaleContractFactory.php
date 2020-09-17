<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------
use App\Models\Company;
use App\Models\SaleContract as Contract;
use App\Models\CompanyBankAccount as BankAccount;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( Contract::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->mas_section_id = 1;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->contract_price                 = $faker->randomFloat( 2, 50000, 200000);
    $data->contract_price_building        = $faker->randomFloat( 2, 50000, 200000);
    $data->contract_price_building_no_tax = $faker->boolean();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->customer_name    = "{$faker->lastName} {$faker->firstName}";
    $data->customer_address = $faker->address;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $houseMaker = Company::where( 'kind_house_maker', true )->get()->random();
    $data->house_maker = $houseMaker->id;
    // ----------------------------------------------------------------------
    $profession = Company::where( 'kind_profession', true )->get()->random();
    $data->register = $profession->id;
    // ----------------------------------------------------------------------
    $contractor = Company::where( 'kind_contractor', true )->get()->random();
    $data->outdoor_facility_manufacturer = $contractor->id;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->person_himself = rand(1, 6);
    $data->person_himself_attachment = rand(0, 1);
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->purchase_date = $faker->dateTimeThisYear('now');
    $data->contract_date = $faker->dateTimeThisYear('now');
    $data->payment_date  = $faker->dateTimeThisYear('now');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->delivery_price   = $faker->randomFloat( 2, 50000, 100000);
    $data->delivery_date    = $data->payment_date;
    $data->delivery_status  = $faker->numberBetween(1, 3);
    // ----------------------------------------------------------------------
    $bankAccount = BankAccount::all()->random();
    $data->delivery_account = $bankAccount->id;
    // ----------------------------------------------------------------------
    $data->delivery_note = $faker->sentence();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->real_estate_tax_income = $faker->numberBetween( 50000, 200000);
    $data->real_estate_tax_income_date = $faker->dateTimeThisYear('now');
    $data->real_estate_tax_income_status = $faker->numberBetween(1, 3);
    // ----------------------------------------------------------------------
    $bankAccount = BankAccount::all()->random();
    $data->real_estate_tax_income_account = $bankAccount->id;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------

$factory->state(Contract::class, 'init', [
    'id'                                => null,
    'mas_section_id'                    => null,
    'contract_price'                    => 0,
    'contract_price_building'           => 0,
    'contract_price_building_no_tax'    => null,
    'customer_name'                     => null,
    'customer_address'                  => null,
    'house_maker'                       => null,
    'register'                          => null,
    'outdoor_facility_manufacturer'     => null,
    'person_himself'                    => null,
    'person_himself_attachment'         => null,
    'purchase_date'                     => null,
    'contract_date'                     => null,
    'payment_date'                      => null,
    'delivery_price'                    => 0,
    'delivery_date'                     => null,
    'delivery_status'                   => null,
    'delivery_account'                  => null,
    'delivery_note'                     => null,
    'real_estate_tax_income'            => 0,
    'real_estate_tax_income_date'       => null,
    'real_estate_tax_income_status'     => null,
    'real_estate_tax_income_account'    => null,
]);

$factory->state(Contract::class, 'relations', [
    'mediations'    => [],
    'deposits'      => [],
    'purchases'     => [],
    'residences'    => [],
    'fee'           => (object) [],
]);
