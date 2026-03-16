<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(protected Project $model) {}

    public function getAllActive(?string $category = null): Collection
    {
        $query = $this->model->active()->orderBy('sort_order')->orderBy('created_at', 'desc');

        if ($category && $category !== 'all') {
            $query->byCategory($category);
        }

        return $query->get();
    }

    public function getAdminList(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->withTrashed()->orderBy('sort_order')->orderBy('created_at', 'desc');

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', "%{$filters['search']}%");
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Project
    {
        return $this->model->find($id);
    }

    public function create(array $data): Project
    {
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $this->model->max('sort_order') + 1;
        }
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->model->findOrFail($id);
        $project->update($data);
        return $project->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function reorder(array $order): void
    {
        foreach ($order as $position => $id) {
            $this->model->where('id', $id)->update(['sort_order' => $position + 1]);
        }
    }
}
