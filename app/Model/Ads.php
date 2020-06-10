<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = "ads";
    protected $fillables = ["ads_type_id","image","description","slug"];
}
