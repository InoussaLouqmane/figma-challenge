<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Inscription d'un challenger",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","country"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *             @OA\Property(property="country", type="string", example="Benin")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Utilisateur inscrit avec succès"),
     *     @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'country' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'country' => $validated['country'],
            'role' => 'challenger',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
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
     *     @OA\Response(response=422, description="Identifiants invalides")
     * )
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

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
     *     summary="Déconnexion de l'utilisateur",
     *     tags={"Authentification"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Déconnecté avec succès")
     * )
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Obtenir les informations de l'utilisateur connecté",
     *     tags={"Authentification"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(response=200, description="Données utilisateur renvoyées")
     * )
     */
    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($request->user());
    }
}

