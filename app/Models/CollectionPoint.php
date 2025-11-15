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
        'verified',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}