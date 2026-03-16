<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function __construct(protected ServiceService $serviceService) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->serviceService->getAllServices()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'icon'        => 'required|string|max:100',
            'items'       => 'nullable',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);
        $service = $this->serviceService->createService($data);
        return response()->json(['data' => $service, 'message' => 'Service créé.'], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:500',
            'icon'        => 'sometimes|string|max:100',
            'items'       => 'nullable',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);
        $service = $this->serviceService->updateService($id, $data);
        return response()->json(['data' => $service, 'message' => 'Service mis à jour.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->serviceService->deleteService($id);
        return response()->json(['message' => 'Service supprimé.']);
    }
}
