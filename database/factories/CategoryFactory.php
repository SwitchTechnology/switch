<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Category;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->name,
        'slug' => Str::slug($name,'-').'-'.uniqid(),
        'website_id' => App\Model\Website::find(rand(1,10))->slug,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
