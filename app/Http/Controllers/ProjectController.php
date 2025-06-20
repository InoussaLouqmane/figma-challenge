<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;




class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Lister tous les projets",
     *     tags={"Projects"},
     *     @OA\Response(response=200, description="Liste des projets")
     * )
     */
    public function index()
    {
        return Project::with('challenge')->latest()->get();
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Créer un nouveau projet",
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "category", "challenge_id", "deadline"},
     *             @OA\Property(property="title", type="string", example="Repenser une plateforme de transport"),
     *             @OA\Property(property="description", type="string", example="Créer une expérience fluide pour les utilisateurs"),
     *             @OA\Property(property="cover", type="string", example="project.png"),
     *             @OA\Property(property="challenge_id", type="integer", example=1),
     *             @OA\Property(property="category", type="string", example="transport"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-06-10"),
     *             @OA\Property(property="deadline", type="string", format="date", example="2025-06-20")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Projet créé")
     * )
     */
    public function store(StoreProjectRequest $request): \Illuminate\Http\JsonResponse
    {
        $project = Project::create($request->validated());
        return response()->json(['message' => 'Projet créé', 'data' => $project], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Afficher un projet",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Projet détaillé")
     * )
     */
    public function show($id)
    {
        return Project::with('challenge')->findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     summary="Mettre à jour un projet",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreProjectRequest")
     *     ),
     *     @OA\Response(response=200, description="Projet mis à jour")
     * )
     */
    public function update(UpdateProjectRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->update($request->validated());
        return response()->json(['message' => 'Projet mis à jour', 'data' => $project]);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     summary="Supprimer un projet",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Projet supprimé")
     * )
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        Project::destroy($id);
        return response()->json(['message' => 'Projet supprimé']);
    }
}
