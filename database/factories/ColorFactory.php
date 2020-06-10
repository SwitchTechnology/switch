<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Color;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Color::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->colorName,
        'slug' => Str::slug($name,'-').'-'.uniqid(),
        'website_id' => App\Model\Website::find(rand(1,10))->slug,
        'category_id' => App\Model\Category::find(rand(1,10))->slug,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
