<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function __construct(protected ServiceService $serviceService) {}

    /**
     * GET /api/services
     */
    public function index(): JsonResponse
    {
        $services = $this->serviceService->getPublicServices();
        return response()->json(['data' => $services]);
    }
}
