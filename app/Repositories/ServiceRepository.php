<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(protected Service $model) {}

    public function getAllActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getAll(): Collection
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function findById(int $id): ?Service
    {
        return $this->model->find($id);
    }

    public function create(array $data): Service
    {
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $this->model->max('sort_order') + 1;
        }
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Service
    {
        $service = $this->model->findOrFail($id);
        $service->update($data);
        return $service->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
