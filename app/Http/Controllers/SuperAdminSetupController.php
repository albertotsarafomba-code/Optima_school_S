<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\AbonnementPaiement;
use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SuperAdminSetupController extends Controller
{
    private function ensureSetupAvailable(): void
    {
        abort_if(
            User::where('role', 'super_admin')->exists(),
            404
        );

        $key = config('services.super_admin_setup_key');

        abort_unless(
            is_string($key) && $key !== '',
            503,
            'La configuration initiale du Super Admin est indisponible.'
        );
    }

    public function create(): View
    {
        $this->ensureSetupAvailable();

        return view('super-admin.setup');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureSetupAvailable();

        $providedKey = $request->input('setup_key');
        $expectedKey = config('services.super_admin_setup_key');

        if (
            !is_string($providedKey) ||
            !is_string($expectedKey) ||
            !hash_equals($expectedKey, $providedKey)
        ) {
            return back()
                ->withErrors([
                    'setup_key' => 'La clé secrète est incorrecte.',
                ])
                ->withInput(
                    $request->except([
                        'setup_key',
                        'password',
                        'password_confirmation',
                    ])
                );
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.min' => 'Le mot de passe doit contenir au moins 12 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput(
                    $request->except([
                        'setup_key',
                        'password',
                        'password_confirmation',
                    ])
                );
        }

        $validated = $validator->validated();

        $user = DB::transaction(function () use ($validated) {
            /*
             * Verrouille une ligne existante pour réduire le risque
             * de deux créations simultanées lors de l'initialisation.
             */
            User::query()
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (User::where('role', 'super_admin')->exists()) {
                abort(404);
            }

            return User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => $validated['password'],
                'role' => 'super_admin',
            ]);
        });

        auth()->login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('super-admin.dashboard')
            ->with('success', 'Le compte Super Administrateur a été créé.');
    }

    public function dashboard(): View
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'super_admin',
            403
        );

        $today = now()->toDateString();

        return view('super-admin.dashboard', [
            'admin' => auth()->user(),

            'establishmentsCount' => Establishment::count(),

            'usersCount' => User::count(),

            'activeSubscriptionsCount' => Abonnement::query()
                ->where('statut', 'actif')
                ->where(function ($query) use ($today) {
                    $query->whereNull('date_expiration')
                        ->orWhereDate('date_expiration', '>=', $today);
                })
                ->count(),

            'pendingPaymentsCount' => AbonnementPaiement::query()
                ->where('statut', 'en_attente')
                ->count(),

            'validatedPaymentsTotal' => AbonnementPaiement::query()
                ->where('statut', 'valide')
                ->sum('montant'),

            'recentEstablishments' => Establishment::query()
                ->with('abonnement')
                ->latest()
                ->take(5)
                ->get(),

            'recentSubscriptions' => Abonnement::query()
                ->with('establishment')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
