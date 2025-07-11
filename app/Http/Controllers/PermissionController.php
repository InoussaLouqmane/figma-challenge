<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;



/**
 * @OA\Post(
 *     path="/api/permissions/{id}",
 *     summary="Gérer les permissions",
 *     security={{"sanctum": {}}, "bearerAuth":{}},
 *     tags={"Permissions"},
 *          @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID de l'utilisateur dont on souhaite changer le rôle",
 *          @OA\Schema(type="integer")
 *      ),
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"role"},
 *              @OA\Property(property="role", type="string", enum={"jury","admin","challenger"}, example="challenger"),
 *          ),
 *     ),
 *     @OA\Response(response=200, description="Soumission faite avec succes"),
 * )
 */
class PermissionController extends Controller
{
    public function update(Request $request, $id){

        if ($request->user()->role != UserRole::Admin) {
            return response()->json([
                'message' =>"Seul un admin peut effectuer cette action"
            ], 401);
        }
        try {
            $request->validate([
                'role' => "required|string|in:admin,challenger,jury",
            ]);

            $user = User::findOrFail($id);
            $user->role = $request->input('role');
            $user->save();

            return response()->json([
                'message' => "Rôle changé avec succès !"
            ], 200);

        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' =>"L'utilisateur n'existe pas !"
            ], 404);
        }catch (\Exception $exception){
            return response()->json([
                'message' => "Une erreur s'est produite",
                'error' => $exception->getMessage()
            ], 500);
        }


    }
}
