<?php
// -----------------------------------------------------------------------------
use App\Models\PjPurchaseResponse as Response;
use Faker\Generator as Faker;
// -----------------------------------------------------------------------------

$factory->define(Response::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(Response::class, 'init', [
    // -------------------------------------------------------------------------
    'id' => null,
    'pj_purchase_target_id' => null,
    // -------------------------------------------------------------------------
    'notices_a' => null,
    'notices_b' => null,
    'notices_c' => null,
    'notices_d' => null,
    'notices_e' => null,
    // -------------------------------------------------------------------------
    'notices_a_text' => null,
    'notices_b_text' => null,
    'notices_c_text' => null,
    'notices_d_text' => null,
    'notices_e_text' => null,
    // -------------------------------------------------------------------------
    'request_permission_a' => null,
    'request_permission_b' => null,
    'request_permission_c' => null,
    'request_permission_d' => null,
    'request_permission_e' => null,
    // -------------------------------------------------------------------------
    'desired_contract_terms_a' => null,
    'desired_contract_terms_b' => null,
    'desired_contract_terms_c' => null,
    'desired_contract_terms_d' => null,
    'desired_contract_terms_e' => null,
    'desired_contract_terms_f' => null,
    'desired_contract_terms_g' => null,
    'desired_contract_terms_h' => null,
    'desired_contract_terms_i' => null,
    'desired_contract_terms_j' => null,
    'desired_contract_terms_k' => null,
    'desired_contract_terms_l' => null,
    'desired_contract_terms_m' => null,
    'desired_contract_terms_m_text' => null,
    'desired_contract_terms_n' => null,
    'desired_contract_terms_o' => null,
    'desired_contract_terms_p' => null,
    'desired_contract_terms_q' => null,
    'desired_contract_terms_r_1' => null,
    'desired_contract_terms_r_1_text' => null,
    'desired_contract_terms_r_2' => null,
    'desired_contract_terms_r_2_text' => null,
    'desired_contract_terms_s' => null,
    'desired_contract_terms_t' => null,
    'desired_contract_terms_u' => null,
    'desired_contract_terms_v' => null,
    'desired_contract_terms_w' => null,
    'desired_contract_terms_x' => null,
    'desired_contract_terms_y' => null,
    'desired_contract_terms_z' => null,
    // -------------------------------------------------------------------------
    'desired_contract_terms_aa' => null,
    'desired_contract_terms_ab' => null,
    'desired_contract_terms_ac' => null,
    'desired_contract_terms_ad' => null,
    'desired_contract_terms_ae' => null,
    'desired_contract_terms_af' => null,
    'desired_contract_terms_ag' => null,
    'desired_contract_terms_ah' => null,
    'desired_contract_terms_ai' => null,
    'desired_contract_terms_aj' => null,
    'desired_contract_terms_ak' => null,
    'desired_contract_terms_al' => null,
    'desired_contract_terms_am' => null,
    'desired_contract_terms_an' => null,
    // -------------------------------------------------------------------------
    'contract_update' => 1,
    // -------------------------------------------------------------------------
]);
