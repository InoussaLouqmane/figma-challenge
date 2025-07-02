<?php

namespace App\Http\Controllers;

use App\Models\RegistrationInfos;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Inscription d'un utilisateur",
     *     tags={"Authentification"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation", "objective",
     *                      "acquisitionChannel", "linkToPortfolio", "figmaSkills", "uxSkills"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *             @OA\Property(property="country", type="string", example="Benin", nullable=true),
     *             @OA\Property(property="role", type="string", example="challenger", nullable=true),
     *             @OA\Property(property="phone", type="string", example="+229 0166662946", nullable=true),
     *
     *
     *             @OA\Property(property="objective", type="string", example="Prendre largent pour aller doter ma femme"),
     *             @OA\Property(property="acquisitionChannel", type="string", example="Twitter"),
     *             @OA\Property(property="linkToPortfolio", type="url", example="https://www.google.com"),
     *             @OA\Property(property="figmaSkills", type="string", enum={"low", "medium", "high"}, example="low"),
     *             @OA\Property(property="uxSkills", type="string", enum={"low", "medium", "high"}, example="low"),
     *
     *         )
     *     ),
     *     @OA\Response(response=201, description="Utilisateur inscrit avec succès"),
     *     @OA\Response(response=422, description="La validation des données a échoué"),
     *     @OA\Response(response=500, description="Erreur du côté serveur")
     * )
     */

    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'country' => 'nullable|string|max:100',
                'role' => 'sometimes|string|max:100',
                'phone' => 'nullable|string|max:100',

                RegistrationInfos::Objective => 'required|string',
                RegistrationInfos::UXSkills => 'required|string|in:low,medium,high',
                RegistrationInfos::FigmaSkills => 'required|string|in:low,medium,high',
                RegistrationInfos::LinkToPortfolio => 'required|url',
                RegistrationInfos::AcquisitionChannel => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'La validation des données a échoué',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $assignedRole = $validated['role'] ?? 'challenger';

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'country' => $validated['country']?? null,
                'role' => $assignedRole,
                'phone' => $validated['phone'] ?? null,
            ]);

            $user->registrationInfos()->create([
                RegistrationInfos::USER_ID => $user->id,
                RegistrationInfos::Objective => $validated[RegistrationInfos::Objective],
                RegistrationInfos::UXSkills => $validated[RegistrationInfos::UXSkills],
                RegistrationInfos::LinkToPortfolio => $validated[RegistrationInfos::LinkToPortfolio],
                RegistrationInfos::AcquisitionChannel => $validated[RegistrationInfos::AcquisitionChannel],
                RegistrationInfos::FigmaSkills => $validated[RegistrationInfos::FigmaSkills],
                RegistrationInfos::FirstAttempt => true,
                RegistrationInfos::isActive => true,
            ]);

            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Une erreur est survenue lors de l’inscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Connexion d'un utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Connexion réussie"),
     *     @OA\Response(response=403, description="Identifiants invalides"),
     *     @OA\Response(response=422, description="La validation des données a échoué")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
        }catch (ValidationException $e){
            return response()->json([
                'message' => 'La validation des données a échoué',
                'errors' => $e->errors(),
            ], 422);
        }



        $user = User::where('email', $validated['email'])->first();
        if($user === null){
            return response()->json([
                'message' => 'Utilisateur non trouve',
            ], 404);
        }
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont invalides.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Déconnexion de l'utilisateur (nécessite le token Bearer)",
     *     tags={"Authentification"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(response=200, description="Déconnecté avec succès")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Déconnexion réussie']);
        }catch (Exception $e){
            return response()->json([
                'message' => 'Une erreur est survenue lors de la deconnexion',
                'errors' => $e->errors(),
            ], 405);
        }



    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Obtenir les informations de l'utilisateur connecté (nécessite le token Bearer)",
     *     tags={"Authentification"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(response=200, description="Données utilisateur renvoyées")
     * )
     */
    public function me(Request $request): JsonResponse
    {

        if (!$request->user()) {
                return response()->json([
                    'message' => 'Non autorisé. Token manquant ou invalide.',
                ], 401);
        }

        try {
            return response()->json($request->user());
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des données utilisateur',
                'errors' => $e->getMessage(), // Affiche l'erreur spécifique
            ], 500); // Si une autre erreur survient, retourne 500
        }
    }

}

