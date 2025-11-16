<?php

namespace App\Http\Repositories;

use App\Models\CollectionPoint;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CollectionPointRepository extends BaseRepository
{
    public function __construct(CollectionPoint $model)
    {
        parent::__construct($model);
    }

    /**
     * Paginate collection points.
     *
     * Assinatura compatível com BaseRepository::paginate(...)
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, $columns);
    }

    public function all(array $columns = ['*']): Collection
    {
        return parent::all($columns);
    }

    public function findNearby(float $lat, float $lng, int $radius = 5000)
    {
        $haversine = "(6371000 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        return $this->model
            ->selectRaw("*, {$haversine} AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->get();
    }

    public function updateOrCreateByCoords(array $attributes, array $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
