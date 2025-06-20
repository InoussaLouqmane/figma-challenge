<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lister tous les utilisateurs",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs",
     *         )
     *     ),
     * )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(User::all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Afficher un utilisateur",
     *     tags={"Users"},
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
    public function show($id): \Illuminate\Http\JsonResponse
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
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Supprimer un utilisateur",
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
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }


    /**
     * @OA\Post (
     *     path="/api/users/create",
     *     summary="ajouter un utilisateur",
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
    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse{
        $user = new User();
        $user->fill($request->validated());
        $user->save();
        return response()->json($user);
    }
}
