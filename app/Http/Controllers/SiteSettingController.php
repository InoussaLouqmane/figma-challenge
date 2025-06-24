<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateSiteSettingRequest;

class SiteSettingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/site-settings",
     *     summary="Afficher les informations générales du site",
     *     tags={"Settings"},
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     @OA\Response(response=200, description="Informations récupérées")
     * )
     */
    public function index()
    {
        try {
            $settings = SiteSetting::all()->first();
            if(!$settings)
                return response()->json([
                    'message' => 'Site Setting not found'
                ], 404);
            return $settings;
        }catch (\Exception $exception){
            return response()->json([
                'message' => 'Une erreur a ete detectee',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/site-settings",
     *     summary="Mettre à jour les informations du site",
     *     security={{"sanctum": {}}, "bearerAuth":{}},
     *     tags={"Settings"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="site_name", type="string", example="Figma Challenge"),
     *             @OA\Property(property="about", type="string", example="Une initiative pour promouvoir le bon design"),
     *             @OA\Property(property="email", type="string", example="contact@figma-challenge.com"),
     *             @OA\Property(property="phone", type="string", example="+22900000000"),
     *             @OA\Property(property="logo", type="string", example="logo.png"),
     *             @OA\Property(property="facebook", type="string", example="https://facebook.com/figmachallenge"),
     *             @OA\Property(property="linkedin", type="string", example="https://linkedin.com/company/figmachallenge"),
     *             @OA\Property(property="github", type="string", example="https://github.com/figma-challenge")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Informations mises à jour")
     * )
     */
    public function update(UpdateSiteSettingRequest $request)
    {
        $settings = SiteSetting::first();

        if (! $settings) {
            $settings = SiteSetting::create($request->validated());
        } else {
            $settings->update($request->validated());
        }

        return response()->json(['message' => 'Paramètres mis à jour', 'data' => $settings]);
    }


}
