<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Vérifier que l'utilisateur possède l'un des rôles autorisés.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Utilisateur non connecté
        |--------------------------------------------------------------------------
        */

        if (!$request->user()) {
            abort(403, 'Accès non autorisé.');
        }


        /*
        |--------------------------------------------------------------------------
        | Vérification du rôle
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $request->user()->role,
            $roles,
            true
        )) {

            abort(
                403,
                'Vous n’avez pas les permissions nécessaires pour accéder à cette page.'
            );
        }


        return $next($request);
    }
}
