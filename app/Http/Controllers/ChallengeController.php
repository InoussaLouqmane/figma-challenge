<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreChallengeRequest;
use App\Http\Requests\UpdateChallengeRequest;
use App\Models\Challenge;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class ChallengeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/challenges",
     *     summary="Lister toutes les éditions",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des éditions retournée avec succès"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Une erreur est survenue lors de la récupération de la liste"
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Vous n'êtes pas authentifié (token invalide)"
     *      )
     *
     * )
     */
    public function index(Request $request)

    {
        $user =  $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Non autorisé. Token manquant ou invalide.',
            ], 401);
        }


        if($user->role !== UserRole::Admin) {
            return response()->json([
                'error' => "Désolé vous n'êtes pas autorisé à accéder à cette ressource",
            ],403);
        }
        try {
            $challenges = Challenge::latest()->get();
            return response()->json($challenges);
        }catch (Exception $exception){
            return response()->json([
                'message' =>  $exception->getMessage(),
            ], 400);
        }

    }

    /**
     * @OA\Post(
     *     path="/api/challenges",
     *     summary="Créer une nouvelle édition",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="Figma Challenge 2025"),
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
        try {

            $challenge = Challenge::create($request->validated());

            return response()->json([
                'message' => 'Challenge créé avec succès',
                'challenge' => $challenge
            ], 201);

        }catch (Exception $exception){
            return response()->json([
                'message' =>  $exception->getMessage(),
            ], 400);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/challenges/{id}",
     *     summary="Voir une édition précise",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
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
        try {
            $challenge = Challenge::findOrFail($id);
            return response()->json($challenge);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Challenge non trouvé',
                'message' => $exception->getMessage(),
            ], 404);
        } catch (Exception $exception) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/challenges/{id}",
     *     summary="Modifier une édition",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
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
        try {

            $challenge = Challenge::findOrFail($id);
            $challenge->update($request->validated());
            return response()->json([
                'message' => 'Challenge mis à jour',
                'challenge' => $challenge
            ]);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Challenge not found',
                'message' => $exception->getMessage(),
            ], 404);

        } catch (ValidationException $exception) {

            return response()->json([
                'error' => 'La validation des donnees a echoue',
                'message' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {

            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/challenges/{id}",
     *     summary="Supprimer une édition",
     *     tags={"Challenges"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
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
    public function destroy(Request $request, $id)
    {
        $user =  $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Non autorisé. Token manquant ou invalide.',
            ], 401);
        }


        if($user->role !== UserRole::Admin) {
            return response()->json([
                'error' => "Privilège insuffisant !",
            ],403);
        }

        try {
            $challenge = Challenge::findOrFail($id);
            $challenge->delete();
            return response()->json(['message' => 'Challenge supprimé']);
        }catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Challenge non trouve',
                'message' => $exception->getMessage(),
            ], 404);
        }catch (Exception $exception) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $exception->getMessage(),
            ],500);
        }

    }
}

