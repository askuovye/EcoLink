<?php

namespace App\Http\Services;

use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\CollectionPointRepository;
use App\Http\Services\GooglePlacesService;

class CollectionPointService extends BaseService
{
    /**
     * Repositório concreto (CollectionPointRepository) mas tipado como BaseRepository
     * para manter compatibilidade com a propriedade da classe pai.
     *
     * @var CollectionPointRepository
     */
    protected BaseRepository $repository;

    private GooglePlacesService $placesService;

    public function __construct(CollectionPointRepository $repository, GooglePlacesService $placesService)
    {
        parent::__construct($repository);
        $this->repository = $repository;
        $this->placesService = $placesService;
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function all(array $columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function show(string $id)
    {
        return $this->repository->show($id);
    }

    public function update(array $data, string $id)
    {
        return $this->repository->update($data, $id);
    }

    public function destroy(string $id)
    {
        $this->repository->destroy($id);
    }

    public function verifyPoint(string $id)
    {
        $point = $this->repository->show($id);
        $point->verified = true;
        $point->save();
        return $point;
    }

    public function findNearby(array $payload)
    {
        $lat = (float)$payload['lat'];
        $lng = (float)$payload['lng'];
        $radius = isset($payload['radius']) ? (int)$payload['radius'] : 5000;

        return $this->repository->findNearby($lat, $lng, $radius);
    }

    public function getNearbyPoints(float $lat, float $lng, int $radius = 5000): array
    {
        $places = $this->placesService->nearby($lat, $lng, $radius);

        $output = [];

        foreach ($places as $place) {
            $attrs = [
                'name' => $place['name'],
                'latitude' => $place['lat'],
                'longitude' => $place['lng'],
            ];

            $values = [
                'name' => $place['name'],
                'latitude' => $place['lat'],
                'longitude' => $place['lng'],
                'address' => $place['address'] ?? null,
            ];

            $this->repository->updateOrCreateByCoords($attrs, $values);

            $output[] = $values;
        }

        return $output;
    }
}
