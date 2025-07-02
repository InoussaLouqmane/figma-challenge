<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\NoteJuryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SoumissionController;
use App\Http\Controllers\UserController;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Formulaire de contact
Route::post('/contact-messages', [ContactMessageController::class, 'store']);

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('/classement', [NoteJuryController::class, 'getClassement']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('challenges', ChallengeController::class);
    Route::apiResource('partners', PartnerController::class);

    Route::apiResource('notes',     NoteJuryController::class);
    Route::apiResource('resources', ResourceController::class);
    //Route::apiResource('notifications', NotificationController::class);


    /**
     * Special routes
     */
    //site-settings
    Route::get('/site-settings', [SiteSettingController::class, 'index']);
    Route::put('/site-settings', [SiteSettingController::class, 'update']);

    //special notes b challenger & classement
    Route::get('/notes-challenger/{id}', [NoteJuryController::class, 'getNotesByChallenger']);
    
    Route::post('/subscribe-project', [SoumissionController::class, 'store']); //s'inscrire à un projet


    //submission
    Route::get('/submissions', [SoumissionController::class, 'index']); //lister les soumissions
    Route::get('/submissions/{id}', [SoumissionController::class, 'show']); // afficher une soumission
    Route::put('/submissions/{id}', [SoumissionController::class, 'update']); //faire ou modifier une soumission

    // Messages de contact peuvent être publics ou protégés selon ton choix :

    Route::get('/contact-messages', [ContactMessageController::class, 'index']); // admin only
    Route::get('/contact-messages/{id}', [ContactMessageController::class, 'show']);
    Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy']);
});

