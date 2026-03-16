<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – BARA DECO (site vitrine)
|--------------------------------------------------------------------------
*/

// ── Routes publiques ────────────────────────────────────────────────────────────

// Auth admin
Route::post('auth/login',  [AuthController::class, 'login']);

// Données publiques du site vitrine
Route::get('projects',  [ProjectController::class, 'index']);   // ?category=interieur
Route::get('services',  [ServiceController::class, 'index']);
Route::post('contact',  [ContactController::class, 'store']);

// ── Routes authentifiées admin ──────────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // Administration (is_admin requis)
    Route::prefix('admin')->middleware('admin')->group(function () {

        // Dashboard stats
        Route::get('stats', [AdminContactController::class, 'stats']);

        // Réalisations (portfolio)
        Route::get('projects',              [AdminProjectController::class, 'index']);
        Route::post('projects',             [AdminProjectController::class, 'store']);
        Route::get('projects/{id}',         [AdminProjectController::class, 'show']);
        Route::post('projects/{id}',        [AdminProjectController::class, 'update']); // POST pour multipart
        Route::delete('projects/{id}',      [AdminProjectController::class, 'destroy']);
        Route::post('projects/reorder',     [AdminProjectController::class, 'reorder']);

        // Services
        Route::get('services',              [AdminServiceController::class, 'index']);
        Route::post('services',             [AdminServiceController::class, 'store']);
        Route::put('services/{id}',         [AdminServiceController::class, 'update']);
        Route::delete('services/{id}',      [AdminServiceController::class, 'destroy']);

        // Messages de contact
        Route::get('contacts',              [AdminContactController::class, 'index']);
        Route::get('contacts/{id}',         [AdminContactController::class, 'show']);
        Route::patch('contacts/{id}/reply', [AdminContactController::class, 'markReplied']);
        Route::delete('contacts/{id}',      [AdminContactController::class, 'destroy']);
    });
});
