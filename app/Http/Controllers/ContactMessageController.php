<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactMessageRequest;

class ContactMessageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contact-messages",
     *     summary="Lister tous les messages de contact",
     *     tags={"Contact Messages"},
     *     @OA\Response(response=200, description="Liste des messages")
     * )
     */
    public function index()
    {
        return ContactMessage::latest()->get();
    }

    /**
     * @OA\Post(
     *     path="/api/contact-messages",
     *     summary="Soumettre un message de contact",
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Message trouvé"),
     *     @OA\Response(response=404, description="Message non trouvé")
     * )
     */
    public function show($id)
    {
        return ContactMessage::findOrFail($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/contact-messages/{id}",
     *     summary="Supprimer un message de contact",
     *     tags={"Contact Messages"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Message supprimé")
     * )
     */
    public function destroy($id)
    {
        ContactMessage::destroy($id);
        return response()->json(['message' => 'Message supprimé']);
    }
}
