<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Services\ServiceService;
use App\Services\ContactService;
use App\Http\Requests\Contact\StoreContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// ─── Projects ────────────────────────────────────────────────────────────────

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    /**
     * GET /api/projects?category=interieur
     */
    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->getPublicProjects(
            $request->query('category')
        );
        return response()->json(['data' => $projects]);
    }
}
