<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LogActivity;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(LogActivity::class, function(Faker $faker){
    return [
        'admin_id'        => 1,
        'activity'        => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'detail'          => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'email'           => $faker->email,
        'ip'              => $faker->ipv4,
        'access_time'     => Carbon::now()
    ];
});
