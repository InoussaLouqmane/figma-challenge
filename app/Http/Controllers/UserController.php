<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use Cloudinary\Cloudinary;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cloudinary\Configuration\Configuration;
use function Cloudinary\Samples\groupsToString;

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
        $user = $request->user();
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
     * @OA\Post(
     *     path="/api/users/{id}",
     *     summary="Mettre à jour un utilisateur",
     *     security={{"sanctum": {}}, {"bearerAuth": {}}},
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
     *         content={
     *             @OA\MediaType(
     *                 mediaType="multipart/form-data",
     *                 @OA\Schema(
     *                     required={},
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                     @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *                     @OA\Property(property="role", type="string", enum={"admin", "jury", "challenger"}, example="challenger"),
     *                     @OA\Property(property="country", type="string", example="Benin"),
     *                     @OA\Property(property="phone", type="string", example="+229 0161786222"),
     *                     @OA\Property(property="bio", type="string", example="Voici ma bio"),
     *                     @OA\Property(property="image", type="file", format="binary", description="Image à uploader")
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response=200, description="Utilisateur mis à jour"),
     *     @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'  => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
                'url' => [
                    'secure' => true]]]);

        try {
            $user = User::findOrFail($id);

            $dataToUpdate = collect($request->except(['image']))
                ->filter(fn ($v) => $v !== '')
                ->toArray();

            Log::info(print_r($dataToUpdate, true));

            if ($request->has('password')) {
                $dataToUpdate['password'] = bcrypt($request->password);
            }

            // Gestion simplifiée d'image sans Cloudinary
            if ($request->hasFile('image') && $request->file('image')->isValid()) {

                if ($user->photo_id) {
                    $cloudinary->uploadApi()->destroy($user->photo_id);
                }

                $resp = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath());

                $dataToUpdate['photo_url'] = $resp['secure_url'];
                $dataToUpdate['photo_id'] = $resp['public_id']; // facultatif
            }

            $user->update($dataToUpdate);

            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => $user
            ]);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
                'error' => $exception->getMessage()
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Données de validation invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $exception) {
            Log::error('Erreur lors de la mise à jour de l\'utilisateur: ' . $exception->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'error' => $exception->getMessage()
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
        try {
            $user = User::findOrFail($id);

            // Si l'utilisateur a une image stockée sur Cloudinary
            if ($user->photo_id) {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                        'api_key'  => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                        'url' => [
                            'secure' => true]]]);

                $cloudinary->uploadApi()->destroy($user->photo_id);
            }

            $user->delete();

            return response()->json(['message' => 'Utilisateur supprimé avec succès']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $exception) {
            Log::error('Erreur lors de la suppression de l\'utilisateur: ' . $exception->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'error' => $exception->getMessage()
            ], 500);
        }
    }



}
