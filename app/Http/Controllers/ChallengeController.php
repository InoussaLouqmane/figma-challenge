<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChallengeRequest;
use App\Http\Requests\UpdateChallengeRequest;
use App\Models\Challenge;
use Illuminate\Http\Request;


class ChallengeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/challenges",
     *     summary="Lister toutes les éditions",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des éditions retournée avec succès"
     *     )
     * )
     */
    public function index()
    {
        $challenges = Challenge::latest()->get();
        return response()->json($challenges);
    }

    /**
     * @OA\Post(
     *     path="/api/challenges",
     *     summary="Créer une nouvelle édition",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "edition", "status"},
     *             @OA\Property(property="title", type="string", example="Figma Challenge 2025"),
     *             @OA\Property(property="edition", type="string", example="2025"),
     *             @OA\Property(property="description", type="string", example="Une édition spéciale..."),
     *             @OA\Property(property="cover", type="string", example="cover.jpg"),
     *             @OA\Property(property="status", type="string", enum={"draft", "open", "closed"}, example="open"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-08-31")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Challenge créé avec succès"),
     *     @OA\Response(response=422, description="Données invalides")
     * )
     */
    public function store(StoreChallengeRequest $request): \Illuminate\Http\JsonResponse
    {
        $challenge = Challenge::create($request->validated());

        return response()->json([
            'message' => 'Challenge créé avec succès',
            'challenge' => $challenge
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/challenges/{id}",
     *     summary="Voir une édition précise",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'édition",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Challenge trouvé"),
     *     @OA\Response(response=404, description="Challenge introuvable")
     * )
     */
    public function show($id)
    {
        $challenge = Challenge::findOrFail($id);
        return response()->json($challenge);
    }

    /**
     * @OA\Put(
     *     path="/api/challenges/{id}",
     *     summary="Modifier une édition",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du challenge à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Nouvel intitulé"),
     *             @OA\Property(property="edition", type="string", example="2025"),
     *             @OA\Property(property="description", type="string", example="Description mise à jour"),
     *             @OA\Property(property="cover", type="string", example="new-cover.jpg"),
     *             @OA\Property(property="status", type="string", enum={"draft", "open", "closed"}, example="closed"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-09-30")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Challenge mis à jour"),
     *     @OA\Response(response=404, description="Introuvable"),
     *     @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function update(UpdateChallengeRequest $request, $id)
    {
        $challenge = Challenge::findOrFail($id);
        $challenge->update($request->validated());

        return response()->json([
            'message' => 'Challenge mis à jour',
            'challenge' => $challenge
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/challenges/{id}",
     *     summary="Supprimer une édition",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'édition à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Challenge supprimé"),
     *     @OA\Response(response=404, description="Introuvable")
     * )
     */
    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);
        $challenge->delete();

        return response()->json(['message' => 'Challenge supprimé']);
    }
}

