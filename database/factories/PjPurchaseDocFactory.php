<?php
// -----------------------------------------------------------------------------
use App\Models\PjPurchaseDoc as Doc;
// -----------------------------------------------------------------------------
use Faker\Generator as Faker;
// -----------------------------------------------------------------------------
use Carbon\Carbon;
// -----------------------------------------------------------------------------

$factory->define(Doc::class, function (Faker $faker) {

    $start_date = Carbon::now();

    return [
        // ---------------------------------------------------------------------
        'pj_purchase_target_id' => 1,
        // ---------------------------------------------------------------------
        'heads_up_a' => $faker->numberBetween(1,3),
        'heads_up_b' => $faker->numberBetween(1,3),
        'heads_up_c' => $faker->numberBetween(1,3),
        'heads_up_d' => $faker->numberBetween(1,3),
        'heads_up_e' => $faker->numberBetween(1,3),
        'heads_up_f' => $faker->numberBetween(1,5),
        'heads_up_g' => $faker->numberBetween(0,1),
        'heads_up_status' => $faker->numberBetween(0,1),
        'heads_up_memo'   => $faker->sentence($nbWords = 6, $variableNbWords = true),
        // ---------------------------------------------------------------------
        'properties_description_a' => $faker->numberBetween(1,4),
        'properties_description_b' => $faker->numberBetween(0,1),
        'properties_description_c' => $faker->numberBetween(0,1),
        'properties_description_d' => $faker->numberBetween(1,3),
        'properties_description_e' => $faker->numberBetween(1,3),
        'properties_description_f' => $faker->numberBetween(1,3),
        'properties_description_status' => $faker->numberBetween(0,1),
        'properties_description_memo'   => $faker->sentence($nbWords = 6, $variableNbWords = true),
        // ---------------------------------------------------------------------
        'road_size_contract_a' => $faker->numberBetween(0,1),
        'road_size_contract_b' => $faker->numberBetween(0,1),
        'road_size_contract_c' => $faker->numberBetween(0,1),
        'road_size_contract_d' => $faker->numberBetween(0,1),
        'road_type_contract_a' => $faker->numberBetween(0,1),
        'road_type_contract_b' => $faker->numberBetween(0,1),
        'road_type_contract_c' => $faker->numberBetween(0,1),
        'road_type_contract_d' => $faker->numberBetween(0,1),
        'road_type_contract_e' => $faker->numberBetween(0,1),
        'road_type_contract_f' => $faker->numberBetween(0,1),
        'road_type_contract_g' => $faker->numberBetween(0,1),
        'road_type_contract_h' => $faker->numberBetween(0,1),
        'road_type_contract_i' => $faker->numberBetween(0,1),
        'road_type_sub2_contract_a' => $faker->numberBetween(0,1),
        'road_type_sub2_contract_b' => $faker->numberBetween(0,1),
        'road_type_sub1_contract'   => $faker->numberBetween(0,1),
        'road_type_sub3_contract'   => $faker->numberBetween(0,1),
        'front_road_f'      => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'front_road_status' => $faker->numberBetween(0,1),
        'front_road_memo'   => $faker->sentence($nbWords = 6, $variableNbWords = true),
        // ---------------------------------------------------------------------
        'contract_a' => $faker->numberBetween(0,1),
        'contract_b' => $faker->numberBetween(1,4),
        'contract_c' => $faker->numberBetween(0,1),
        'contract_d' => $faker->numberBetween(0,1),
        'contract_status' => $faker->numberBetween(0,1),
        'contract_memo'   => $faker->sentence($nbWords = 6, $variableNbWords = true),
        // ---------------------------------------------------------------------
        'gathering_request_title'       => $faker->numberBetween(0,1),
        'gathering_request_to'          => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'gathering_request_to_check'    => $faker->numberBetween(0,1),
        'gathering_request_third_party' => $faker->numberBetween(0,1),
        // ---------------------------------------------------------------------
        'notices_a' => 1,
        'notices_b' => null,
        'notices_c' => null,
        'notices_d' => 1,
        'notices_e' => 1,
        'notices_f' => 1,
        // ---------------------------------------------------------------------
        'request_permission_a' => 1,
        'request_permission_b' => 1,
        'request_permission_c' => 1,
        'request_permission_d' => 1,
        'request_permission_e' => 0,
        // ---------------------------------------------------------------------
        'desired_contract_terms_a'  => null,
        'desired_contract_terms_b'  => null,
        'desired_contract_terms_c'  => null,
        'desired_contract_terms_d'  => null,
        'desired_contract_terms_e'  => null,
        'desired_contract_terms_f'  => null,
        'desired_contract_terms_g'  => null,
        'desired_contract_terms_h'  => null,
        'desired_contract_terms_i'  => null,
        'desired_contract_terms_j'  => null,
        'desired_contract_terms_k'  => null,
        'desired_contract_terms_l'  => 1,
        'desired_contract_terms_m'  => null,
        'desired_contract_terms_n'  => null,
        'desired_contract_terms_o'  => null,
        'desired_contract_terms_p'  => null,
        'desired_contract_terms_q'  => null,
        'desired_contract_terms_r'  => null,
        'desired_contract_terms_s'  => null,
        'desired_contract_terms_t'  => null,
        'desired_contract_terms_u'  => null,
        'desired_contract_terms_v'  => null,
        'desired_contract_terms_w'  => null,
        'desired_contract_terms_x'  => null,
        'desired_contract_terms_y'  => null,
        'desired_contract_terms_z'  => null,
        'desired_contract_terms_aa' => null,
        'desired_contract_terms_ab' => null,
        // ---------------------------------------------------------------------
        'optional_items_a' => null,
        'optional_items_b' => null,
        'optional_items_c' => null,
        'optional_items_d' => null,
        'optional_items_e' => null,
        'optional_items_f' => null,
        'optional_items_g' => null,
        'optional_items_h' => 1,
        'optional_items_i' => 1,
        'optional_items_j' => null,
        'optional_items_k' => null,
        'optional_memo_content' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        // ---------------------------------------------------------------------
        'desired_contract_date' => $faker->dateTimeBetween($start_date, $end_date = $start_date->addDays(10), $timezone = null),
        'settlement_date'       => $faker->dateTimeBetween($end_date, $end_date = $end_date->addDays(10), $timezone = null),
        'expiration_date'       => $faker->dateTimeBetween($end_date, $end_date = $end_date->addDays(10), $timezone = null),
        // ---------------------------------------------------------------------
        'gathering_request_status' => $faker->numberBetween(0,1),
        'gathering_request_memo'   => $faker->sentence($nbWords = 6, $variableNbWords = true),
        // ---------------------------------------------------------------------
    ];
});

$factory->state(Doc::class, 'init', [
    // -------------------------------------------------------------------------
    'id' => null,
    'pj_purchase_target_id' => null,
    // -------------------------------------------------------------------------
    'heads_up_a' => null,
    'heads_up_b' => null,
    'heads_up_c' => null,
    'heads_up_d' => null,
    'heads_up_e' => null,
    'heads_up_f' => null,
    'heads_up_g' => null,
    'heads_up_status' => null,
    'heads_up_memo'   => null,
    // -------------------------------------------------------------------------
    'properties_description_a' => null,
    'properties_description_b' => null,
    'properties_description_c' => null,
    'properties_description_d' => null,
    'properties_description_e' => null,
    'properties_description_f' => null,
    'properties_description_status' => null,
    'properties_description_memo'   => null,
    // -------------------------------------------------------------------------
    'road_size_contract_a' => null,
    'road_size_contract_b' => null,
    'road_size_contract_c' => null,
    'road_size_contract_d' => null,
    'road_type_contract_a' => null,
    'road_type_contract_b' => null,
    'road_type_contract_c' => null,
    'road_type_contract_d' => null,
    'road_type_contract_e' => null,
    'road_type_contract_f' => null,
    'road_type_contract_g' => null,
    'road_type_contract_h' => null,
    'road_type_contract_i' => null,
    'road_type_sub2_contract_a' => null,
    'road_type_sub2_contract_b' => null,
    'road_type_sub1_contract'   => null,
    'road_type_sub3_contract'   => null,
    'front_road_f'      => null,
    'front_road_status' => null,
    'front_road_memo'   => null,
    // -------------------------------------------------------------------------
    'contract_a'        => null,
    'contract_b'        => null,
    'contract_c'        => null,
    'contract_d'        => null,
    'contract_status'   => null,
    'contract_memo'     => null,
    // -------------------------------------------------------------------------
    'gathering_request_title'       => null,
    'gathering_request_to'          => null,
    'gathering_request_to_check'    => null,
    'gathering_request_third_party' => null,
    // -------------------------------------------------------------------------
    'notices_a' => null,
    'notices_b' => null,
    'notices_c' => null,
    'notices_d' => null,
    'notices_e' => null,
    'notices_f' => null,
    // -------------------------------------------------------------------------
    'request_permission_a' => null,
    'request_permission_b' => null,
    'request_permission_c' => null,
    'request_permission_d' => null,
    'request_permission_e' => null,
    // -------------------------------------------------------------------------
    'desired_contract_terms_a'  => null,
    'desired_contract_terms_b'  => null,
    'desired_contract_terms_c'  => null,
    'desired_contract_terms_d'  => null,
    'desired_contract_terms_e'  => null,
    'desired_contract_terms_f'  => null,
    'desired_contract_terms_g'  => null,
    'desired_contract_terms_h'  => null,
    'desired_contract_terms_i'  => null,
    'desired_contract_terms_j'  => null,
    'desired_contract_terms_k'  => null,
    'desired_contract_terms_l'  => null,
    'desired_contract_terms_m'  => null,
    'desired_contract_terms_n'  => null,
    'desired_contract_terms_o'  => null,
    'desired_contract_terms_p'  => null,
    'desired_contract_terms_q'  => null,
    'desired_contract_terms_r'  => null,
    'desired_contract_terms_s'  => null,
    'desired_contract_terms_t'  => null,
    'desired_contract_terms_u'  => null,
    'desired_contract_terms_v'  => null,
    'desired_contract_terms_w'  => null,
    'desired_contract_terms_x'  => null,
    'desired_contract_terms_y'  => null,
    'desired_contract_terms_z'  => null,
    'desired_contract_terms_aa' => null,
    'desired_contract_terms_ab' => null,
    // -------------------------------------------------------------------------
    'optional_items_a' => null,
    'optional_items_b' => null,
    'optional_items_c' => null,
    'optional_items_d' => null,
    'optional_items_e' => null,
    'optional_items_f' => null,
    'optional_items_g' => null,
    'optional_items_h' => null,
    'optional_items_i' => null,
    'optional_items_j' => null,
    'optional_items_k' => null,
    'optional_memo_content' => null,
    // -------------------------------------------------------------------------
    'desired_contract_date' => null,
    'settlement_date'       => null,
    'expiration_date'       => null,
    // -------------------------------------------------------------------------
    'gathering_request_status' => null,
    'gathering_request_memo'   => null,
    // -------------------------------------------------------------------------
]);
