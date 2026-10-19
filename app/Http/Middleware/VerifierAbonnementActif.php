<?php

namespace App\Http\Middleware;

use App\Models\Establishment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifierAbonnementActif
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        // Utilisateur non connecté : laisser les autres middlewares gérer l'accès.
        if (!$user) {
            return $next($request);
        }

        // Le Super Admin doit toujours pouvoir gérer les abonnements.
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // La consultation reste toujours autorisée.
        if (!$request->isMethod('POST')
            && !$request->isMethod('PUT')
            && !$request->isMethod('PATCH')
            && !$request->isMethod('DELETE')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        // Autoriser le paiement d'abonnement et la sélection du contexte scolaire.
        $routesAutorisees = [
            'admin.abonnement.paiements.store',
            'school_years.select',
        ];

        if (in_array($routeName, $routesAutorisees, true)) {
            return $next($request);
        }

        // Ne pas appliquer la restriction aux rôles hors établissement scolaire.
        if (!in_array($user->role, [
            'admin',
            'secretariat',
            'comptable',
            'enseignant',
        ], true)) {
            return $next($request);
        }

        // Vérifier l'établissement actuellement sélectionné.
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            return $next($request);
        }

        // Vérifier que l'utilisateur a accès à cet établissement.
        $accessible = $user->role === 'admin'
            ? $user->establishments()->whereKey($establishmentId)->exists()
            : $user->assignedEstablishments()->whereKey($establishmentId)->exists();

        if (!$accessible) {
            abort(
                403,
                'Vous ne disposez pas d’un accès autorisé à cet établissement.'
            );
        }

        $establishment = Establishment::with('abonnement')
            ->find($establishmentId);

        // Sans établissement ou sans date d'expiration, ne rien bloquer.
        $expiration = $establishment?->abonnement?->date_expiration;

        if (!$expiration) {
            return $next($request);
        }

        // Les 15 jours suivant l'expiration restent entièrement accessibles.
        $limite = $expiration->copy()
            ->startOfDay()
            ->addDays(15);

        if (now()->startOfDay()->lessThanOrEqualTo($limite)) {
            return $next($request);
        }

        // À partir du 16e jour : consultation uniquement.
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Abonnement expiré depuis plus de 15 jours. Le logiciel est en lecture seule. Veuillez régler votre abonnement.',
            ], 403);
        }

        return redirect()
            ->back()
            ->with(
                'error',
                'Votre abonnement a dépassé la période de grâce de 15 jours. Le logiciel est en lecture seule. Vous pouvez consulter vos données et effectuer le paiement de votre abonnement.'
            );
    }
}
