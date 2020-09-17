<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Admin::class, function(Faker $faker){
    static $admin_role_id = 2;
    return [
        'display_name'      => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'admin_role_id'     => $admin_role_id,
        'password'          => bcrypt('12345678'),
        'email_verified_at' => Carbon::now(),
        'created_at'        => Carbon::now(),
        'updated_at'        => Carbon::now(),
    ];
});
