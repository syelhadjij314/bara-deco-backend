<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    public function getPublicProjects(?string $category = null): Collection
    {
        return $this->projectRepository->getAllActive($category);
    }

    public function getAdminProjects(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->projectRepository->getAdminList($filters, $perPage);
    }

    public function getProject(int $id): Project
    {
        $project = $this->projectRepository->findById($id);
        if (!$project) abort(404, 'Projet non trouvé.');
        return $project;
    }

    public function createProject(array $data): Project
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->storeImage($data['image']);
        }

        return $this->projectRepository->create($data);
    }

    public function updateProject(int $id, array $data): Project
    {
        $project = $this->getProject($id);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image
            $this->deleteImage($project->image);
            $data['image'] = $this->storeImage($data['image']);
        }

        return $this->projectRepository->update($id, $data);
    }

    public function deleteProject(int $id): bool
    {
        $project = $this->getProject($id);
        $this->deleteImage($project->image);
        return $this->projectRepository->delete($id);
    }

    public function reorderProjects(array $order): void
    {
        $this->projectRepository->reorder($order);
    }

    // ─── Private Helpers ────────────────────────────────────────────────────────

    private function storeImage(UploadedFile $file): string
    {
        $path = $file->store('projects', 'public');
        return Storage::url($path);
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (!$imageUrl) return;
        $path = str_replace('/storage/', '', parse_url($imageUrl, PHP_URL_PATH));
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
