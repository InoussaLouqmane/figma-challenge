<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use App\Models\NotificationUser;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNotificationRequest;

class NotificationController extends Controller
{
    /*
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Lister toutes les notifications",
     *     tags={"Notifications"},
     *     @OA\Response(response=200, description="Liste des notifications"),
     *     @OA\Response(response=404, description="Aucune notif trouvee")
     * )
     //
    public function index()
    {
        $notifications = Notification::latest()->get();

        if($notifications){
            return $notifications;
        }else{
            return response()->json([
                'message' => 'Aucune notif trouvee'
            ], 404);
        }
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
     *             @OA\Property(property="audience", type="string", enum={"all","jury","admin","challenger"}, example="challenger"),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-06-24T12:00:00"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Notification créée"),
     * )
     //
    public function store(StoreNotificationRequest $request)
    {
        $notification = Notification::create($request->validated());
        return response()->json([
            'message' => 'Notification créée',
            'data' => $notification
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/notifications/{id}",
     *     summary="Afficher une notification",
     *     tags={"Notifications"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de la notification"),
     *     @OA\Response(response=404, description="Notification non trouvée"),
     * )
     //
    public function show($id)
    {
        try {

            $notification = Notification::findOrFail($id);
            return $notification;

        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Notification non trouvée'
            ], 404);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Supprimer une notification",
     *     tags={"Notifications"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Notification supprimée")
     * )
     //
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            return response()->json([
                'message' => 'Notification supprimée'
            ], 200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Notification non trouvée'
            ], 404);
        }

    }


    /**
     * @OA\PUT(
     *     path="/api/notifications/{id}",
     *     summary="Marquer une notification comme lue",
     *     tags={"Notifications"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_id",},
     *              @OA\Property(property="user_id", type="integer", example="1"),
     *          )
     *      ),
     *     @OA\Response(response=200, description="Notification supprimée")
     * )
     //
    public function update(UpdateNotificationRequest $request, $id)
    {

        $notification = Notification::findOrFail($id);

        $userId = $request->input('user_id');


        $notificationUser = NotificationUser::where('notification_id', $id)
            ->where('user_id', $userId)
            ->first();

        // Si aucune correspondance : retour 404
        if (!$notificationUser) {
            return response()->json([
                'message' => 'Aucune correspondance entre l’utilisateur et cette notification'
            ], 404);
        }

        // Si déjà lue
        if ($notificationUser->read_at) {
            return response()->json([
                'message' => 'Notification déjà lue'
            ], 409);
        }


        $notificationUser->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'message' => 'Notification marquée comme lue',
            'data' => $notificationUser
        ]);
    }
*/
}
