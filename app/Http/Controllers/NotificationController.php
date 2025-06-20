<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNotificationRequest;

class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Lister toutes les notifications",
     *     tags={"Notifications"},
     *     @OA\Response(response=200, description="Liste des notifications")
     * )
     */
    public function index()
    {
        return Notification::latest()->get();
    }

    /**
     * @OA\Post(
     *     path="/api/notifications",
     *     summary="Créer une notification",
     *     tags={"Notifications"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "audience"},
     *             @OA\Property(property="title", type="string", example="Deadline demain !"),
     *             @OA\Property(property="content", type="string", example="N'oubliez pas de soumettre votre projet avant minuit."),
     *             @OA\Property(property="audience", type="string", example="challengers"),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-06-24T12:00:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Notification créée")
     * )
     */
    public function store(StoreNotificationRequest $request)
    {
        $notification = Notification::create($request->validated());
        return response()->json(['message' => 'Notification créée', 'data' => $notification], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/notifications/{id}",
     *     summary="Afficher une notification",
     *     tags={"Notifications"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de la notification")
     * )
     */
    public function show($id)
    {
        return Notification::findOrFail($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Supprimer une notification",
     *     tags={"Notifications"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Notification supprimée")
     * )
     */
    public function destroy($id)
    {
        Notification::destroy($id);
        return response()->json(['message' => 'Notification supprimée']);
    }
}
