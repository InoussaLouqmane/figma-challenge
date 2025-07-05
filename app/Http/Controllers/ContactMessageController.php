<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactMessageRequest;

class ContactMessageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contact-messages",
     *     summary="Lister tous les messages de contact",
     *     tags={"Contact Messages"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(response=200, description="Liste des messages")
     * )
     */
    public function index(Request $request)
    {
        $user =  $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Non autorisé. Token manquant ou invalide.',
            ], 401);
        }


        if($user->role !== UserRole::Admin) {
            return response()->json([
                'error' => "Privilège insuffisant !",
            ],403);
        }
        try {
            $contactMessages = ContactMessage::all();
            if($contactMessages->isEmpty()){
                return response()->json([
                    'message' => 'Aucun message trouve'
                ], 404);
            }
            return ContactMessage::latest()->get();

        }catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * @OA\Post(
     *     path="/api/contact-messages",
     *     summary="Soumettre un message de contact",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Contact Messages"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "message"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jane@example.com"),
     *             @OA\Property(property="message", type="string", example="Bonjour, j'ai une question concernant...")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Message enregistré")
     * )
     */
    public function store(StoreContactMessageRequest $request)
    {

        $message = ContactMessage::create($request->validated());
        return response()->json(['message' => 'Message reçu', 'data' => $message], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contact-messages/{id}",
     *     summary="Afficher un message de contact",
     *     tags={"Contact Messages"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Message trouvé"),
     *     @OA\Response(response=404, description="Message non trouvé")
     * )
     */
    public function show(Request $request,$id)
    {
        $user =  $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Non autorisé. Token manquant ou invalide.',
            ], 401);
        }


        if($user->role !== UserRole::Admin) {
            return response()->json([
                'error' => "Privilège insuffisant !",
            ],403);
        }

        try {
            return ContactMessage::findOrFail($id);
        }catch(ModelNotFoundException $exception){
            return response()->json([
                'message' => 'Le message n\'existe pas',
                'erreur' => $exception->getMessage()
            ], 404);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/contact-messages/{id}",
     *     summary="Supprimer un message de contact",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Contact Messages"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Message supprimé")
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user =  $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Non autorisé. Token manquant ou invalide.',
            ], 401);
        }


        if($user->role !== UserRole::Admin) {
            return response()->json([
                'error' => "Privilège insuffisant !",
            ],403);
        }

        try {
            $contactMessage = ContactMessage::findOrFail($id);
            $contactMessage->delete();

            return response()->json([
                'message' => 'Message supprimé'
            ], 200);

        }catch(ModelNotFoundException $exception){
            return response()->json([
                'message' => 'Le message n\'existe pas',
            ], 404);
        }


    }
}
