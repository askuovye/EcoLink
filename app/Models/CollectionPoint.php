<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    protected $table = 'points';
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'type',
        'operating_hours',
    ];
}