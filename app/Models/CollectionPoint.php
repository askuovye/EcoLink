<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CollectionPoint extends Model
{
    protected $table = 'points';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'operating_hours',
        'verified',
        'user_id',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'verified'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('verified', true);
    }

    public function scopeWithinRadius(Builder $query, float $lat, float $lng, int $radius = 5000): Builder
    {
        $haversine = "(6371000 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        return $query->selectRaw("*, {$haversine} AS distance", [$lat, $lng, $lat])
                     ->having('distance', '<=', $radius)
                     ->orderBy('distance', 'asc');
    }
}
