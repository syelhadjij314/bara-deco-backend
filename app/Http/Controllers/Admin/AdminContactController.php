<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function __construct(protected ContactService $contactService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'search']);
        return response()->json($this->contactService->getMessages($filters));
    }

    public function show(int $id): JsonResponse
    {
        $contact = $this->contactService->getMessage($id);
        // Auto-mark as read when opened
        $contact = $this->contactService->readMessage($id);
        return response()->json(['data' => $contact]);
    }

    public function markReplied(int $id): JsonResponse
    {
        $contact = $this->contactService->replyMessage($id);
        return response()->json(['data' => $contact, 'message' => 'Marqué comme répondu.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->contactService->deleteMessage($id);
        return response()->json(['message' => 'Message supprimé.']);
    }

    public function stats(): JsonResponse
    {
        return response()->json(['data' => $this->contactService->getStats()]);
    }
}
