<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = "incomes";
    protected $fillables = ["website_id","user_id","amount","description","slug"];
}
