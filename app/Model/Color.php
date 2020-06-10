<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = "colors";
    protected $fillables = ["name","slug","website_id","category_id"];
}
