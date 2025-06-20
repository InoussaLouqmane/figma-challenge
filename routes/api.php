<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\NoteJuryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SoumissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('challenges', ChallengeController::class);
    Route::apiResource('submissions', SoumissionController::class);
    Route::apiResource('notes', NoteJuryController::class);
    Route::apiResource('resources', ResourceController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('site-settings', SiteSettingController::class);

    // Messages de contact peuvent être publics ou protégés selon ton choix :
    Route::post('/contact', [ContactMessageController::class, 'store']);
    Route::get('/contact', [ContactMessageController::class, 'index']); // admin only
    Route::get('/contact/{id}', [ContactMessageController::class, 'show']);
    Route::delete('/contact/{id}', [ContactMessageController::class, 'destroy']);
});

