<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Website;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

$factory->define(Website::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->name,
        'slug' => Str::slug($name,'-').'-'.uniqid(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
