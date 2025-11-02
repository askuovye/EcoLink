<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    protected $table = 'points';
    protected $fillable = ['name', 'latitude', 'longitude', 'operating_hours', 'address'];
}