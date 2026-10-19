<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Vérifier que l'utilisateur possède l'un des rôles autorisés,
     * puis appliquer les restrictions liées à l'abonnement.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        if (!$request->user()) {
            abort(403, 'Accès non autorisé.');
        }

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

        return app(VerifierAbonnementActif::class)
            ->handle($request, $next);
    }
}
