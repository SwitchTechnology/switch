<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $fillables = ["product_id","customer_name","phone","count","fulladdress","description","user_id","slug"];
}
