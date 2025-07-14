<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Cloudinary\Cloudinary;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;



class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Lister tous les projets et les participants",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\Response(response=200, description="Liste des projets")
     * )
     */
    public function index()
    {

        $projects = Project::with(['soumissions.user'])->get();

        $result = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'title' => $project->title,
                'description' => $project->description,
                'objective' => $project->objective,
                'cover_url' => $project->cover_url,
                'category' => $project->category,
                'start_date' => $project->start_date,
                'deadline' => $project->deadline,
                'status' => $project->status,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'participants' => $project->soumissions->map(function ($s) {
                    return [

                            'id' => $s->user->id,
                            'name' => $s->user->name,
                            'email' => $s->user->email,
                            'figma_link' => $s->figma_link ?? null,
                    ];
                }),
            ];
        });

        return response()->json(["data" => $result]);

    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Créer un nouveau projet",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType (
     *              mediaType="multipart/form-data",
     *                 @OA\Schema(
     *                    required={"title", "category", "deadline", "objective", "description"},
     *              @OA\Property(property="title", type="string", example="Repenser la plateforme YANGO"),
     *              @OA\Property(property="status", type="string", enum={"active", "closed"}, example="active"),
     *              @OA\Property(property="description", type="string", example="Créer une expérience fluide pour les utilisateurs"),
     *              @OA\Property(property="objective", type="string", example="L objectif est de former des guerriers"),
     *              @OA\Property(property="cover", type="file", format="binary", description="Image à uploader"),
     *              @OA\Property(property="category", type="string", example="transport"),
     *              @OA\Property(property="start_date", type="string", format="date", example="2025-06-10"),
     *              @OA\Property(property="deadline", type="string", format="date", example="2025-06-20")
     *                 )
     *
     *
     *         )
     *     ),
     *     @OA\Response(response=201, description="Projet créé"),
     *     @OA\Response(response=422, description="La validation des donnees a echoue")
     * )
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'  => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
                'url' => [
                    'secure' => true]]]);

        $data = $request->validated();

        // Gestion du cover image
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $resp = $cloudinary->uploadApi()->upload($request->file('cover')->getRealPath());

            $data['cover_url'] = $resp['secure_url'];
            $data['cover_id'] = $resp['public_id'];
        }

        $project = Project::create($data);

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
     * @OA\Post(
     *     path="/api/projects/{id}",
     *     summary="Mettre à jour un projet",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType (
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={},
     *                 @OA\Property(property="title", type="string", example="Repenser la plateforme YANGO"),
     *                 @OA\Property(property="status", type="string", enum={"active", "closed"}, example="active"),
     *                 @OA\Property(property="description", type="string", example="Créer une expérience fluide pour les utilisateurs"),
     *                 @OA\Property(property="objective", type="string", example="L objectif est de former des guerriers"),
     *                 @OA\Property(property="cover", type="file", format="binary", description="Image à uploader"),
     *                 @OA\Property(property="category", type="string", example="transport"),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-06-10"),
     *                 @OA\Property(property="deadline", type="string", format="date", example="2025-06-20")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Projet mis à jour"),
     *     @OA\Response(response=404, description="Le projet que vous recherchez n\'existe pas")
     * )
     */

    public function update(UpdateProjectRequest $request, $id): JsonResponse
    {
        try {

            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'  => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                    'url' => [
                        'secure' => true]]]);

            $project = Project::findOrFail($id);
            $data = $request->validated();


            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                if ($project->cover_id) {
                    $cloudinary->uploadApi()->destroy($project->cover_id);
                }

                $resp = $cloudinary->uploadApi()->upload($request->file('cover')->getRealPath());
                $data['cover_url'] = $resp['secure_url'];
                $data['cover_id'] = $resp['public_id'];
            }

            $project->update($data);

            return response()->json([
                'message' => 'Projet mis à jour',
                'data' => $project
            ]);
        } catch (ModelNotFoundException $exception) {
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

            if($project->cover_id){
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                        'api_key'  => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                        'url' => [
                            'secure' => true]]]);

                $resp = $cloudinary->uploadApi()->destroy($project->cover_id);
            }
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
