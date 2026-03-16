<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService) {}

    /**
     * POST /api/contact
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->submitContact($request->validated());

        return response()->json([
            'message' => 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.',
            'data'    => ['id' => $contact->id],
        ], 201);
    }
}
