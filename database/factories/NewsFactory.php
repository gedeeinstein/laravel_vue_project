<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\News::class, function (Faker $faker) {
    return [
        'admin_id'      => 2,
        'title'         => $faker->words(rand(3, 5), true),
        'body'          => $faker->paragraph,
        'image'         => 'uploads/' . $faker->image(public_path('uploads'),400,300, 'abstract', false),
        'publish_date'  => Carbon::now()->subMonths(rand(1,3))->subDays(rand(1,30)),
        'status'        => 'publish'
    ];

});
