<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;



class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Lister tous les projets",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\Response(response=200, description="Liste des projets")
     * )
     */
    public function index()
    {
        return Project::all();
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Créer un nouveau projet",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "category", "challenge_id", "deadline"},
     *             @OA\Property(property="title", type="string", example="Repenser la plateforme YANGO"),
     *             @OA\Property(property="challenge_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", enum={"active", "closed"}, example="active"),
     *             @OA\Property(property="description", type="string", example="Créer une expérience fluide pour les utilisateurs"),
     *             @OA\Property(property="cover", type="string", example="project.png"),
     *             @OA\Property(property="category", type="string", example="transport"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-06-10"),
     *             @OA\Property(property="deadline", type="string", format="date", example="2025-06-20")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Projet créé"),
     *     @OA\Response(response=422, description="La validation des donnees a echoue")
     * )
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {

            $project = Project::create($request->validated());

            return response()->json([
                'message' => 'Projet créé',
                'data' => $project

            ], 201);


    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Afficher un projet",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Projet détaillé")
     * )
     */
    public function show($id)
    {
        try {
            return Project::findOrFail($id);
        }catch (ModelNotFoundException $exception){
            return response()->json([
                'message' => 'Le projet que vous recherchez n\'existe pas',

            ], 404);
        }

    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     summary="Mettre à jour un projet",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreProjectRequest")
     *     ),
     *     @OA\Response(response=200, description="Projet mis à jour"),
     *     @OA\Response(response=404, description="Le projet que vous recherchez n\'existe pas")
     * )
     */
    public function update(UpdateProjectRequest $request, $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $project->update($request->validated());
            return response()->json(['message' => 'Projet mis à jour', 'data' => $project]);
        }catch (ModelNotFoundException $exception){
            return response()->json([
                'message' => 'Le projet que vous recherchez n\'existe pas',
            ], 404);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     summary="Supprimer un projet",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Projet supprimé"),
     *     @OA\Response(response=404, description="Le projet que vous recherchez n'existe pas")
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json(['message' => 'Projet supprimé']);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Le projet que vous recherchez n\'existe pas',
                'error' => $exception->getMessage(),
            ], 404);
        }
    }
}
