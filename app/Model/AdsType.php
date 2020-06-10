<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdsType extends Model
{
    protected $table = "ads_types";
    protected $fillables = ["name","slug"];
}
