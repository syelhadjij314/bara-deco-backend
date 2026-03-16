<?php

namespace App\Repositories\Interfaces;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    public function getAllActive(): Collection;
    public function getAll(): Collection;
    public function findById(int $id): ?Service;
    public function create(array $data): Service;
    public function update(int $id, array $data): Service;
    public function delete(int $id): bool;
}
