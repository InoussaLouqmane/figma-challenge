<?php

namespace App\Http\Controllers;


use App\Enums\UserRole;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lister tous les utilisateurs",
     *     tags={"Users"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs",
     *         )
     *     ),
     * )
     */
    public function index(Request $request): JsonResponse
    {

        $user =  $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Non autorisé. Token manquant ou invalide.',
            ], 401);
        }


        if($user->role === UserRole::Challenger) {
            return response()->json([
                'error' => "Désolé vous n'êtes pas autorisé à accéder à cette ressource",
            ],403);
        }
        return response()->json(User::all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Afficher un utilisateur",
     *     tags={"Users"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'utilisateur",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    public function show($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Mettre à jour un utilisateur",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "role", "country"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="role", type="string", enum={"admin", "jury", "challenger"}, example="challenger"),
     *             @OA\Property(property="country", type="string", example="Benin"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Utilisateur mis à jour"),
     *     @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);

            $user->update($request->validated());
            return response()->json($user);

        }catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
                'error' => $exception->getMessage()
            ], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Supprimer un utilisateur",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Utilisateur supprimé"),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    /*
    /**
     * @OA\Post (
     *     path="/api/users/create",
     *     summary="ajouter un utilisateur",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Users"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation","country"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="12345678"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *              @OA\Property(property="country", type="string", example="Benin"),
     *              @OA\Property(property="role", type="string", example="challenger", nullable=true),
     *              @OA\Property(property="phone", type="string", example="+229 0166662946", nullable=true)
     *          )
     *      ),
     *      @OA\Response(response=201, description="Utilisateur inscrit avec succès"),
     *      @OA\Response(response=404, description="Utilisateur non trouvé")
     *      @OA\Response(response=422, description="La validation des données a échoué"),
     *      @OA\Response(response=500, description="Erreur du côté serveur")
     * )
     */

    /*public function store(StoreUserRequest $request): JsonResponse{
        $user = new User();
        $user->fill($request->validated());
        $user->save();
        return response()->json($user);
    }*/
}
