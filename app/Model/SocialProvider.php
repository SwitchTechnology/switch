<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SocialProvider extends Model
{
    protected $table = "social_providers";
    protected $fillables = ["user_id","provider_id","provider"];

    function user(){
        return $this->belongsTo(User::class);
    }
}
