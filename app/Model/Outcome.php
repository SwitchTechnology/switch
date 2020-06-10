<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    protected $table = "outcomes";
    protected $fillables = ["website_id","user_id","amount","description","slug"];
}
