<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\NoteJury;
use App\Models\Project;
use App\Models\Soumission;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNoteJuryRequest;

class NoteJuryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notes",
     *     summary="Les notes attribués par chacun des membres du jury",
     *     tags={"Notes Jury"},
     *     @OA\Response(response=200, description="Liste des notes")
     * )
     */
    public function index(Request $request)
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
     *             required={"jury_id", "soumission_id", "graphisme", "animation", "navigation"},
     *             @OA\Property(property="jury_id", type="integer", example=2),
     *             @OA\Property(property="soumission_id", type="integer", example=5),
     *             @OA\Property(property="graphisme", type="integer", example=30),
     *             @OA\Property(property="animation", type="integer", example=8),
     *             @OA\Property(property="navigation", type="integer", example=10),
     *             @OA\Property(property="commentaire", type="string", example="Très bon travail globalement")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Note enregistrée avec succès"),
     *     @OA\Response(response=409, description="Désolé, note déjà enregistrée!")
     * )
     */
    public function store(StoreNoteJuryRequest $request)
    {

        $user = User::findOrFail($request[NoteJury::COL_USER_ID]);

        //vérifier si c'est bien un jury, sinon délivrer un message explicite
        if($user->role == UserRole::Jury || $user->role == UserRole::Admin) {
            // vérifier si la requête n'existe déjà pas, pour éviter de duplicate conflict
            $exist = NoteJury::where(NoteJury::COL_USER_ID, $request[NoteJury::COL_USER_ID])
                ->where(NoteJury::COL_SOUMISSION_ID, $request[NoteJury::COL_SOUMISSION_ID])
                ->exists();
            ;
            if($exist){
                return response()->json([
                    'message' => 'Désolé, note déjà enregistrée!'
                ], 409);
            }
            $note = NoteJury::create($request->validated());
            return response()->json(['message' => 'Note enregistrée', 'data' => $note], 201);
        }else{
            return response()->json([
                'message' => "Désolé, vous devez être un membre du jury ou un admin, pour effectuer cette action"
            ], 403);
        }

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

    /**
     * @OA\Put(
     *     path="/api/notes/{id}",
     *     summary="Modifier une note",
     *     tags={"Notes Jury"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID de la note",
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"jury_id", "soumission_id", "graphisme", "animation", "navigation"},
     *             @OA\Property(property="jury_id", type="integer", example=2),
     *             @OA\Property(property="soumission_id", type="integer", example=5),
     *             @OA\Property(property="graphisme", type="integer", example=15),
     *             @OA\Property(property="animation", type="integer", example=8),
     *             @OA\Property(property="navigation", type="integer", example=10),
     *             @OA\Property(property="commentaire", type="string", example="Très beau travail globalement")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Note enregistrée avec succès"),
     *     @OA\Response(response=403, description="Vous n'avez pas les permissions requises!"),
     *     @OA\Response(response=404, description="Note non trouvée"),
     *     @OA\Response(response=409, description="Une incohérence dans la requête, vous n\'êtes pas responsable de cette note")
     * )
     */
    public function update(StoreNoteJuryRequest $request, $id): \Illuminate\Http\JsonResponse
    {

        //récupération de la note concernée
        $note = NoteJury::findOrFail($id);

        //vérifier les accès
        $user = User::findOrFail($request[NoteJury::COL_USER_ID]);


        if(($user->role != UserRole::Jury) && ($user->role != UserRole::Admin))
        {
            return response()->json([
                'message' => "Désolé, vous devez être un membre du jury, pour effectuer cette action"
            ], 403);
        }


        //vérifier si la note est avait bien attribuée par le membre de jury qui souhaite l'éditer
        if(!($note->jury_id == $user->id && $note->soumission_id == $request[NoteJury::COL_SOUMISSION_ID])){
            return response()->json([
                'message' => 'Une incohérence dans la requête, vous n\'êtes pas responsable de cette note'
            ], 409);
        }

        $data = $request->validated();
        unset($data[NoteJury::COL_USER_ID], $data[NoteJury::COL_SOUMISSION_ID]);
        $note->update($data);
        return response()->json([
            'message' => 'Note modifiée avec succès'
        ], 200);

    }




    /**
     * @OA\Get(
     *     path="/api/notes-challenger/{id}",
     *     summary="Récupérer les notes d'un challenger",
     *     tags={"Notes par challenger / classement"},
     *     @OA\Parameter(name="id", in="path", description="ID du challenger", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de la note")
     * )
     */
    public function getNotesByChallenger($userId): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ]);
        }

        $soumission = Soumission::where('user_id', $userId)
            ->latest()
            ->first();

        if (!$soumission) {
            return response()->json([
                'message' => 'Aucune soumission trouvée pour ce challenger.'
            ], 404);
        }

        $notes = NoteJury::where('soumission_id', $soumission->id)->get();

        if ($notes->isEmpty()) {
            return response()->json([
                'message' => 'Aucune note trouvée pour cette soumission.'
            ], 404);
        }

        $noteCount = $notes->count();

        $summary = [
            NoteJury::COL_GRAPHISME => round($notes->sum(NoteJury::COL_GRAPHISME) / $noteCount, 2),
            NoteJury::COL_ANIMATION => round($notes->sum(NoteJury::COL_ANIMATION) / $noteCount, 2),
            NoteJury::COL_NAVIGATION => round($notes->sum(NoteJury::COL_NAVIGATION) / $noteCount, 2),
        ];

        $summary['total'] = round(array_sum($summary), 2); // total moyen sur 3 critères

        $details = $notes->map(function ($note) {
            return [
                'id_jury' => $note->jury_id,
                'graphisme' => $note->graphisme,
                'animation' => $note->animation,
                'navigation' => $note->navigation,
            ];
        });

        return response()->json([
            'id_challenger' => $userId,
            'soumission_id' => $soumission->id,
            'notes_summary' => $summary,
            'notes_details' => $details,
        ]);
    }




    /**
     * @OA\Get(
     *     path="/api/classement/",
     *     summary="Afficher le classement global",
     *     tags={"Notes par challenger / classement"},
     *     @OA\Response(response=200, description="Détail de la note")
     * )
     */
    public function getClassement()
    {
        $classements = [];

        $projects = Project::with('soumissions.user')->get();

        foreach ($projects as $project) {
            $scores = collect();

            foreach ($project->soumissions as $soumission) {
                $notes = $soumission->notes;

                $noteCount = $notes->count();
                if ($noteCount === 0) {
                    continue;
                }

                // Calcul des moyennes mais on garde les noms de champs d'origine
                $graphisme = round($notes->sum('graphisme') / $noteCount, 2);
                $animation = round($notes->sum('animation') / $noteCount, 2);
                $navigation = round($notes->sum('navigation') / $noteCount, 2);
                $total = round($graphisme + $animation + $navigation, 2);

                $scores->push([
                    'challenger_id' => $soumission->user->id,
                    'name' => $soumission->user->name,
                    'country' => $soumission->user->country ?? null,
                    'total_graphisme' => $graphisme,
                    'total_animation' => $animation,
                    'total_navigation' => $navigation,
                    'total_points' => $total,
                ]);
            }

            $sorted = $scores->sortByDesc('total_points')->values();

            $ranked = $sorted->map(function ($item, $index) {
                return array_merge(['position' => $index + 1], $item);
            });

            $classements[] = [
                'project_id' => $project->id,
                'project_title' => $project->title,
                'challenger_count' => $ranked->count(),
                'rank' => $ranked
            ];
        }

        return response()->json($classements);
    }



}
