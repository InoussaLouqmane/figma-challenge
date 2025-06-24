<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {

            if ($request->is('api/*')) {
                return true; // Always render JSON for 'api/*' routes
            }
            return $request->expectsJson();
        });


        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'message' => 'Vous n\'êtes pas authentifié(e) ou token invalide.',
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé(e) à effectuer cette action.',
            ], 403);
        });

        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            // Check if the exception message contains anything related to login
            if (str_contains($e->getMessage(), 'login')) {
                return response()->json(
                    [
                        'message' => 'Vous n\'êtes pas autorisé | Token Invalide'
                    ],
                    401
                );
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Endpoint not found.'
                ],
                404
            );
        });


    })->create();
