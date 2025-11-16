<?php

namespace App\Http\Services;

use App\Http\Repositories\BaseRepository;

abstract class BaseService
{
    protected BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
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
}
