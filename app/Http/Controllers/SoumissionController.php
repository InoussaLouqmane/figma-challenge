<?php

namespace App\Http\Controllers;

use App\Models\Soumission;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSoumissionRequest;
use App\Http\Requests\UpdateSoumissionRequest;

class SoumissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/soumissions",
     *     summary="Lister toutes les soumissions",
     *     tags={"Soumissions"},
     *     @OA\Response(response=200, description="Liste des soumissions")
     * )
     */
    public function index()
    {
        return Soumission::with(['user', 'project'])->latest()->get();
    }

    /**
     * @OA\Post(
     *     path="/api/soumissions",
     *     summary="Soumettre un projet",
     *     tags={"Soumissions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "project_id", "challenge_id", "inscription_date"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="project_id", type="integer", example=3),
     *             @OA\Property(property="challenge_id", type="integer", example=2),
     *             @OA\Property(property="inscription_date", type="string", format="date-time", example="2025-06-15T18:00:00"),
     *             @OA\Property(property="figma_link", type="string", example="https://figma.com/file/xyz"),
     *             @OA\Property(property="soumission_date", type="string", format="date-time"),
     *             @OA\Property(property="commentaire", type="string"),
     *             @OA\Property(property="status", type="string", example="soumis")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Soumission enregistrée")
     * )
     */
    public function store(StoreSoumissionRequest $request)
    {
        $soumission = Soumission::create($request->validated());
        return response()->json(['message' => 'Soumission enregistrée', 'data' => $soumission], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/soumissions/{id}",
     *     summary="Afficher une soumission",
     *     tags={"Soumissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Soumission détaillée")
     * )
     */
    public function show($id)
    {
        return Soumission::with(['user', 'project'])->findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/soumissions/{id}",
     *     summary="Mettre à jour une soumission",
     *     tags={"Soumissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreSoumissionRequest")
     *     ),
     *     @OA\Response(response=200, description="Soumission mise à jour")
     * )
     */
    public function update(UpdateSoumissionRequest $request, $id)
    {
        $soumission = Soumission::findOrFail($id);
        $soumission->update($request->validated());
        return response()->json(['message' => 'Soumission mise à jour', 'data' => $soumission]);
    }

    /**
     * @OA\Delete(
     *     path="/api/soumissions/{id}",
     *     summary="Supprimer une soumission",
     *     tags={"Soumissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Soumission supprimée")
     * )
     */
    public function destroy($id)
    {
        Soumission::destroy($id);
        return response()->json(['message' => 'Soumission supprimée']);
    }
}
