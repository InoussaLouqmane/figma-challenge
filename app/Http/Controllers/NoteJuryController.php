<?php

namespace App\Http\Controllers;

use App\Models\NoteJury;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNoteJuryRequest;

class NoteJuryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notes",
     *     summary="Lister toutes les notes de jury",
     *     tags={"Notes Jury"},
     *     @OA\Response(response=200, description="Liste des notes")
     * )
     */
    public function index()
    {
        return NoteJury::with(['jury', 'soumission'])->get();
    }

    /**
     * @OA\Post(
     *     path="/api/notes",
     *     summary="Attribuer une note",
     *     tags={"Notes Jury"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "soumission_id", "graphisme", "animation", "navigation"},
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="soumission_id", type="integer", example=5),
     *             @OA\Property(property="graphisme", type="integer", example=9),
     *             @OA\Property(property="animation", type="integer", example=8),
     *             @OA\Property(property="navigation", type="integer", example=10),
     *             @OA\Property(property="commentaire", type="string", example="Très bon travail globalement")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Note enregistrée")
     * )
     */
    public function store(StoreNoteJuryRequest $request)
    {
        $note = NoteJury::create($request->validated());
        return response()->json(['message' => 'Note enregistrée', 'data' => $note], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/notes/{id}",
     *     summary="Afficher une note",
     *     tags={"Notes Jury"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de la note")
     * )
     */
    public function show($id)
    {
        return NoteJury::with(['jury', 'soumission'])->findOrFail($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/notes/{id}",
     *     summary="Supprimer une note",
     *     tags={"Notes Jury"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Note supprimée")
     * )
     */
    public function destroy($id)
    {
        NoteJury::destroy($id);
        return response()->json(['message' => 'Note supprimée']);
    }
}
