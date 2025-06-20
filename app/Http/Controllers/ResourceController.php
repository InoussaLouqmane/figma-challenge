<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;



class ResourceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/resources",
     *     summary="Lister toutes les ressources",
     *     tags={"Resources"},
     *     @OA\Response(response=200, description="Liste des ressources")
     * )
     */
    public function index()
    {
        return Resource::latest()->get();
    }

    /**
     * @OA\Post(
     *     path="/api/resources",
     *     summary="Ajouter une ressource",
     *     tags={"Resources"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "link", "type", "category"},
     *             @OA\Property(property="title", type="string", example="Guide d'accessibilité"),
     *             @OA\Property(property="description", type="string", example="Ce guide explique les bonnes pratiques."),
     *             @OA\Property(property="link", type="string", example="https://example.com/resource.pdf"),
     *             @OA\Property(property="type", type="string", example="pdf"),
     *             @OA\Property(property="category", type="string", example="externe"),
     *             @OA\Property(property="visible_at", type="string", format="date-time", example="2025-06-24T00:00:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ressource ajoutée")
     * )
     */
    public function store(StoreResourceRequest $request)
    {
        $resource = Resource::create($request->validated());
        return response()->json(['message' => 'Ressource ajoutée', 'data' => $resource], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/resources/{id}",
     *     summary="Afficher une ressource",
     *     tags={"Resources"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de la ressource")
     * )
     */
    public function show($id)
    {
        return Resource::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/resources/{id}",
     *     summary="Mettre à jour une ressource",
     *     tags={"Resources"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreResourceRequest")
     *     ),
     *     @OA\Response(response=200, description="Ressource mise à jour")
     * )
     */
    public function update(UpdateResourceRequest $request, $id)
    {
        $resource = Resource::findOrFail($id);
        $resource->update($request->validated());
        return response()->json(['message' => 'Ressource mise à jour', 'data' => $resource]);
    }

    /**
     * @OA\Delete(
     *     path="/api/resources/{id}",
     *     summary="Supprimer une ressource",
     *     tags={"Resources"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Ressource supprimée")
     * )
     */
    public function destroy($id)
    {
        Resource::destroy($id);
        return response()->json(['message' => 'Ressource supprimée']);
    }
}
