<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class PartnerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/partners",
     *     summary="Liste des partenaires",
     *     tags={"Partners"},
     *     @OA\Response(response=200, description="Liste des partenaires")
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Partner::all(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/partners/{id}",
     *     summary="Afficher un partenaire",
     *     tags={"Partners"},
     *     @OA\Parameter(name="id", in="path", description="ID du partenaire à afficher", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détails du partenaire"),
     *     @OA\Response(response=404, description="Partenaire non trouvé")
     * )
     */
    public function show($id)
    {
        try {
            $partner = Partner::findOrFail($id);
            return response()->json($partner);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/partners",
     *     summary="Ajouter un partenaire",
     *     description="Permet d'enregistrer un nouveau partenaire pour un challenge donné.",
     *     tags={"Partners"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "logo", "type"},
     *             @OA\Property(property="name", type="string", example="MTN Corp", description="Nom du partenaire"),
     *             @OA\Property(property="logo", type="string", example="mtn-logo.png", description="Nom du fichier logo du partenaire"),
     *             @OA\Property(property="description", type="string", example="MTN est une entreprise leader dans le domaine des télécommunications.", description="Description facultative du partenaire"),
     *             @OA\Property(property="type", type="string", enum={"gold","vip","standard"}, example="gold", description="Type de partenariat. Peut être : gold, vip ou standard"),
     *             @OA\Property(property="website", type="string", format="url", example="https://www.mtn.com", description="Site web officiel du partenaire (optionnel)"),
     *             @OA\Property(property="visible", type="boolean", example=true, description="Indique si le partenaire est visible publiquement"),
     *             @OA\Property(property="position", type="integer", example=4, description="Position d'affichage du partenaire dans une liste triée (optionnel)")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Partenaire créé avec succès")
     * )
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'logo' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'website' => 'nullable|string',
            'visible' => 'boolean',
            'position' => 'nullable|integer',
        ]);

        $latestChallenge = Challenge::latest()->first();

        if (!$latestChallenge) {
            return response()->json([
                'message' => 'Aucun challenge disponible pour lier ce partenaire'
            ], 400);
        }

        $data['challenge_id'] = $latestChallenge->id;

        $partner = Partner::create($data);

        return response()->json([
            'message' => 'Partenaire créé',
            'data' => $partner
        ], 201);
    }


    /**
     * @OA\Put(
     *     path="/api/partners/{id}",
     *     summary="Mettre à jour un partenaire",
     *     description="Permet de modifier les informations d'un partenaire existant.",
     *     tags={"Partners"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID du partenaire à modifier", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="MTN Business", description="Nouveau nom du partenaire"),
     *             @OA\Property(property="logo", type="string", example="mtn-new-logo.png", description="Logo du partenaire (nom du fichier)"),
     *             @OA\Property(property="description", type="string", example="Partenaire technologique majeur.", description="Description mise à jour"),
     *             @OA\Property(property="type", type="string", enum={"gold","vip","standard"}, example="vip", description="Type de partenariat modifié"),
     *             @OA\Property(property="website", type="string", format="url", example="https://business.mtn.com", description="Site web mis à jour"),
     *             @OA\Property(property="visible", type="boolean", example=false, description="Met à jour la visibilité du partenaire"),
     *             @OA\Property(property="position", type="integer", example=2, description="Nouvelle position dans l'affichage trié")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Partenaire mis à jour avec succès"),
     *     @OA\Response(response=404, description="Partenaire non trouvé")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $partner = Partner::findOrFail($id);

            $data = $request->validate([
                'name' => 'sometimes|required|string',
                'logo' => 'sometimes|required|string',
                'description' => 'nullable|string',
                'type' => 'sometimes|required|string',
                'website' => 'nullable|string',
                'visible' => 'boolean',
                'position' => 'nullable|integer',
            ]);

            $partner->update($data);

            return response()->json(['message' => 'Partenaire mis à jour', 'data' => $partner]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/partners/{id}",
     *     summary="Supprimer un partenaire",
     *     description="Supprime un partenaire existant via son identifiant.",
     *     tags={"Partners"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID du partenaire à supprimer", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Partenaire supprimé avec succès"),
     *     @OA\Response(response=404, description="Partenaire non trouvé")
     * )
     */
    public function destroy($id)
    {
        try {
            $partner = Partner::findOrFail($id);
            $partner->delete();

            return response()->json(['message' => 'Partenaire supprimé']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
    }
}
