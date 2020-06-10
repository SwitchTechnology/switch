<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $fillables = ["website_id","name","description","total_count","amount","discount","price_per_product","slug"];
}
