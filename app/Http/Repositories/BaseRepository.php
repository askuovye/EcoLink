<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function show(string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function update(array $data, string $id): Model
    {
        $model = $this->show($id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function destroy(string $id): void
    {
        $this->show($id)->delete();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function findBy(array $attributes)
    {
        return $this->model->where($attributes)->get();
    }
}
