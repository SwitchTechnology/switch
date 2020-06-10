<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = "websites";
    protected $fillables = ['name', 'slug', 'user_id', 'banner_images', 'description', 'about', 'contact'];
}
