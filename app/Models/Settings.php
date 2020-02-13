<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable =[
        'header_title','header_img','icon_img'
    ];
}
