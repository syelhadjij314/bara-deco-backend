<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'is_active', 'search']);
        return response()->json($this->projectService->getAdminProjects($filters));
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->validated());
        return response()->json(['data' => $project, 'message' => 'Réalisation ajoutée.'], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(['data' => $this->projectService->getProject($id)]);
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        $project = $this->projectService->updateProject($id, $request->validated());
        return response()->json(['data' => $project, 'message' => 'Réalisation mise à jour.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->projectService->deleteProject($id);
        return response()->json(['message' => 'Réalisation supprimée.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        $this->projectService->reorderProjects($request->order);
        return response()->json(['message' => 'Ordre mis à jour.']);
    }
}
