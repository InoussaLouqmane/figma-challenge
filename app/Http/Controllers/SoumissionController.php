<?php

namespace App\Http\Controllers;

use App\Enums\SoumissionStatus;
use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Soumission;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSoumissionRequest;
use App\Http\Requests\UpdateSoumissionRequest;

class SoumissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/submissions",
     *     summary="Lister toutes les soumissions",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Submissions"},
     *     @OA\Response(response=200, description="Liste des soumissions")
     * )
     */
    public function index()
    {
        $projects = Project::with(['soumissions.user', 'soumissions.notes'])->get();

        $result = $projects->flatMap(function ($project) {
            return $project->soumissions->filter(function ($soumission) {
                return !empty($soumission->figma_link); // Garder uniquement les soumissions valides
            })->map(function ($soumission) use ($project) {
                $totalGraphisme = $soumission->notes->sum('graphisme');
                $totalAnimation = $soumission->notes->sum('animation');
                $totalNavigation = $soumission->notes->sum('navigation');

                return [
                    'project_id' => $project->id,
                    'project_title' => $project->title,
                    'project_cover' => $project->cover,
                    'project_deadline' => $project->deadline,
                    'canEdit' => now()->lessThan($project->deadline),

                    'challenger_id' => $soumission->user->id,
                    'challenger_name' => $soumission->user->name,
                    'submission_id' => $soumission->id,
                    'submission_date' => optional($soumission->soumission_date)->format('Y-m-d H:i'),
                    'submission_status' => $soumission->status,
                    'submission_comment' => $soumission->commentaire,
                    'figma_link' => $soumission->figma_link,

                    'notes' => [
                        'graphisme' => $totalGraphisme,
                        'animation' => $totalAnimation,
                        'navigation' => $totalNavigation,
                    ]
                ];
            });
        })->values();

        return response()->json([
            'data' => $result
        ]);
    }



    /**
     * @OA\Post(
     *     path="/api/subscribe-project",
     *     summary="S'inscrire à un projet",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "project_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="project_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Inscription avec succes"),
     *     @OA\Response(response=409, description="Déjà inscrit à un projet")
     * )
     */
    public function storeSubscribe(StoreSoumissionRequest $request)
    {
        $userId = $request->input('user_id');

        $user = \App\Models\User::findOrFail($userId);

        if($user->role == UserRole::Jury )
        {
            return response()->json([
                'error' => "Vous n'êtes pas autorisé à faire cette action."
            ], 403);
        }

        $projectId = $request->input('project_id');
        $challengeId = $request->input('challenge_id');

        $exists = Soumission::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Oh! Vous êtes déjà inscrit à un projet.'
            ], 409);
        }

        $soumission = Soumission::create($request->validated());

        return response()->json([
            'message' => 'Inscription avec succès',
            'data' => $soumission
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/submissions/{id}",
     *     summary="Afficher une soumission",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Submissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Soumission détaillée")
     * )
     */
    public function show($id)
    {
            $soumission = Soumission::where('id', $id)
            ->latest()->first();

            if(!$soumission) {
                return response()->json([
                    'message' => 'Soumission introuvable'
                ], 404);
            }
            return $soumission;
    }

    /**
     * @OA\Put(
     *     path="/api/submissions/{id}",
     *     summary="Modifier une soumission",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Submissions"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID de la soumission à modifier",
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSoumissionRequest")
     *     ),
     *     @OA\Response(response=200, description="Soumission faite avec succes")
     * )
     */
    public function update(UpdateSoumissionRequest $request, $id)
    {
        $soumission = Soumission::where(Soumission::COL_ID,$id)
            ->latest()->first();

        if($request['figma_link']){
            $request['status'] = SoumissionStatus::Soumis->value;
            $request[Soumission::COL_SOUMISSION_DATE] = date('Y-m-d H:i');
        }
        $soumission->update($request->validated());

        return response()->json([
            'message' => 'Soumission modifiée avec succes',
            'data' => $soumission]);
    }



    /**
     * @OA\Post(
     *     path="/api/submissions",
     *     summary="Faire une soumission",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Submissions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSoumissionRequest")
     *     ),
     *     @OA\Response(response=200, description="Soumission faite avec succes")
     * )
     */
    public function storeSoumission(UpdateSoumissionRequest $request)
    {
        try {
            $soumission = Soumission::where(Soumission::COL_USER_ID, $request['user_id'])
                ->latest()
                ->first();

            if (!$soumission) {
                return response()->json([
                    'message' => "Vous n'êtes inscrit à aucun projet."
                ], 404);
            }

            $data = $request->validated();

            if (!empty($data['figma_link'])) {
                $data['status'] = SoumissionStatus::Soumis->value;
                $data[Soumission::COL_SOUMISSION_DATE] = now();
            }

            $soumission->update($data);

            return response()->json([
                'message' => 'Soumission faite avec succès',
                'data' => $soumission
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Erreur lors de la soumission',
                'error' => $exception->getMessage()
            ], 500);
        }
    }


    /*
     * @OA\Delete(
     *     path="/api/submissions/{id}",
     *     summary="Supprimer une soumission",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Submissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Soumission supprimée")
     * )
     *
    public function destroy($id)
    {
        Soumission::destroy($id);
        return response()->json(['message' => 'Soumission supprimée']);
    }*/
}
