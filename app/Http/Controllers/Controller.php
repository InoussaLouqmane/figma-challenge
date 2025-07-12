<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *    title="Figma Challenge apis",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Schema(
 *     schema="StoreProjectRequest",
 *     required={"title", "category", "challenge_id", "deadline"},
 *     @OA\Property(property="title", type="string", example="Projet Mobilité"),
 *     @OA\Property(property="description", type="string", example="Une plateforme pour organiser le transport urbain"),
 *     @OA\Property(property="category", type="string", example="transport"),
 *     @OA\Property(property="cover", type="string", example="project-cover.png"),
 *     @OA\Property(property="challenge_id", type="integer", example=1),
 *     @OA\Property(property="start_date", type="string", format="date", example="2025-07-01"),
 *     @OA\Property(property="deadline", type="string", format="date", example="2025-07-31")
 * )
 *
 * @OA\Schema(
 *     schema="StoreResourceRequest",
 *     required={"title", "link", "type", "category"},
 *     @OA\Property(property="title", type="string", example="Guide de participation"),
 *     @OA\Property(property="description", type="string", example="Document PDF expliquant les règles"),
 *     @OA\Property(property="link", type="string", format="url", example="https://figma-challenge.bj/ressources/guide.pdf"),
 *     @OA\Property(property="type", type="string", enum={"pdf", "lien", "autre"}, example="pdf"),
 *     @OA\Property(property="category", type="string", enum={"externe", "video", "autre"}, example="externe"),
 *     @OA\Property(property="visible_at", type="string", format="datetime", example="2025-07-01T10:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="StoreChallengeRequest",
 *     required={"title", "edition", "status"},
 *     @OA\Property(property="title", type="string", example="Figma Challenge 2025"),
 *     @OA\Property(property="edition", type="string", example="2025"),
 *     @OA\Property(property="description", type="string", example="Une édition axée sur l'expérience utilisateur"),
 *     @OA\Property(property="cover", type="string", example="challenge-cover.jpg"),
 *     @OA\Property(property="status", type="string", enum={"draft", "open", "closed"}, example="open"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2025-08-31")
 * )
 *
 * @OA\Schema(
 *     schema="StoreSoumissionRequest",
 *     required={"user_id", "project_id"},
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="project_id", type="integer", example=3),
 * )
 *
 *  @OA\Schema(
 *      schema="UpdateSoumissionRequest",
 *      required={"figma_link"},
 *      @OA\Property(property="figma_link", type="string", example="https://figma.com/file/abc123"),
 *      @OA\Property(property="commentaire", type="string", example="Soumission initiale")
 *  )
 *
 * @OA\Schema(
 *     schema="StoreNoteJuryRequest",
 *     required={"user_id", "soumission_id", "graphisme", "animation", "navigation"},
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="soumission_id", type="integer", example=12),
 *     @OA\Property(property="graphisme", type="integer", example=8),
 *     @OA\Property(property="animation", type="integer", example=7),
 *     @OA\Property(property="navigation", type="integer", example=9),
 *     @OA\Property(property="commentaire", type="string", example="Bon travail, mais améliorable sur l’accessibilité")
 * )
 *
 * @OA\Schema(
 *     schema="StoreNotificationRequest",
 *     required={"title", "content", "audience"},
 *     @OA\Property(property="title", type="string", example="Fin des soumissions"),
 *     @OA\Property(property="content", type="string", example="Vous avez jusqu'à ce soir minuit pour soumettre"),
 *     @OA\Property(property="audience", type="string", enum={"all", "challenger", "jury"}, example="challenger"),
 *     @OA\Property(property="scheduled_at", type="string", format="datetime", example="2025-07-30T18:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     required={"name", "email", "password",},
 *     @OA\Property(property="name", type="string", example="Louqmane Inoussa"),
 *     @OA\Property(property="email", type="string", format="email", example="louqmane@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="strongPass123"),
 *     @OA\Property(property="country", type="string", example="Bénin", nullable=true),
 *     @OA\Property(property="phone", type="string", example="+229 97 00 00 00"),
 *     @OA\Property(property="bio", type="string", example="Designer passionné par l’UX"),
 *     @OA\Property(property="role", type="string", enum={"admin", "jury", "challenger"}, example="challenger"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive", "banned"}, example="active")
 * )
 *
 * @OA\Schema(
 *      schema="User",
 *      required={"id"},
 *      @OA\Property(property="id", type="integer", example="12"),
 *  )
 */
abstract class Controller
{
    //
}
