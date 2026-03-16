<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ServiceService
{
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository
    ) {}

    public function getPublicServices(): Collection
    {
        return $this->serviceRepository->getAllActive();
    }

    public function getAllServices(): Collection
    {
        return $this->serviceRepository->getAll();
    }

    public function getService(int $id): Service
    {
        $service = $this->serviceRepository->findById($id);
        if (!$service) abort(404, 'Service non trouvé.');
        return $service;
    }

    public function createService(array $data): Service
    {
        // Normalise items as array
        if (isset($data['items']) && is_string($data['items'])) {
            $data['items'] = array_filter(array_map('trim', explode("\n", $data['items'])));
        }
        return $this->serviceRepository->create($data);
    }

    public function updateService(int $id, array $data): Service
    {
        if (isset($data['items']) && is_string($data['items'])) {
            $data['items'] = array_values(array_filter(array_map('trim', explode("\n", $data['items']))));
        }
        return $this->serviceRepository->update($id, $data);
    }

    public function deleteService(int $id): bool
    {
        return $this->serviceRepository->delete($id);
    }
}
