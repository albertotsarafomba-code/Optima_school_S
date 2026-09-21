<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Establishment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs.
     */
    public function index(Request $request): View
    {
        $query = User::query()
            ->with([
                'assignedEstablishments.schoolType',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $validRoles = array_map(
            fn (UserRole $role) => $role->value,
            UserRole::cases()
        );

        if (
            $request->filled('role') &&
            in_array(
                $request->role,
                $validRoles,
                true
            )
        ) {
            $query->where(
                'role',
                $request->role
            );
        }

        $users = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $roles = UserRole::cases();

        return view(
            'users.index',
            compact(
                'users',
                'roles'
            )
        );
    }

    /**
     * Formulaire de création.
     *
     * L'utilisateur sera automatiquement affecté
     * à l'établissement actuellement sélectionné
     * par l'administrateur.
     */
    public function create(): View|RedirectResponse
    {
        $roles = UserRole::cases();

        /*
        |--------------------------------------------------------------------------
        | Récupérer l'établissement courant
        |--------------------------------------------------------------------------
        */
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            return redirect()
                ->route('establishments.index')
                ->with(
                    'error',
                    'Veuillez sélectionner un établissement avant de créer un utilisateur.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'établissement appartient bien
        | à l'administrateur connecté.
        |--------------------------------------------------------------------------
        */
        $establishment = Establishment::query()
            ->with('schoolType')
            ->where('id', $establishmentId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$establishment) {
            return redirect()
                ->route('establishments.index')
                ->with(
                    'error',
                    'L’établissement actuellement sélectionné ne vous appartient pas.'
                );
        }

        return view(
            'users.create',
            compact(
                'roles',
                'establishment'
            )
        );
    }

    /**
     * Créer un utilisateur.
     *
     * Pour tout utilisateur non-administrateur :
     * l'établissement courant de l'administrateur
     * est automatiquement affecté.
     */
    public function store(Request $request): RedirectResponse
    {
        $validRoles = array_map(
            fn (UserRole $role) => $role->value,
            UserRole::cases()
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in($validRoles),
            ],
        ], [
            'name.required' =>
                'Le nom est obligatoire.',

            'name.string' =>
                'Le nom est invalide.',

            'name.max' =>
                'Le nom ne doit pas dépasser 255 caractères.',

            'email.required' =>
                'L’adresse email est obligatoire.',

            'email.email' =>
                'Veuillez saisir une adresse email valide.',

            'email.max' =>
                'L’adresse email ne doit pas dépasser 255 caractères.',

            'email.unique' =>
                'Cette adresse email est déjà utilisée.',

            'password.required' =>
                'Le mot de passe est obligatoire.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',

            'role.required' =>
                'Le rôle est obligatoire.',

            'role.in' =>
                'Le rôle sélectionné est invalide.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Établissement courant
        |--------------------------------------------------------------------------
        */
        $establishmentId = session('establishment_id');

        /*
        |--------------------------------------------------------------------------
        | Un utilisateur non-admin doit être rattaché
        | à l'établissement courant de l'administrateur.
        |--------------------------------------------------------------------------
        */
        $establishment = null;

        if (
            $validated['role'] !== UserRole::ADMIN->value
        ) {
            if (!$establishmentId) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role' =>
                            'Veuillez sélectionner un établissement avant de créer cet utilisateur.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Vérification de propriété
            |--------------------------------------------------------------------------
            */
            $establishment = Establishment::query()
                ->with('schoolType')
                ->where('id', $establishmentId)
                ->where('user_id', auth()->id())
                ->first();

            if (!$establishment) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role' =>
                            'L’établissement sélectionné ne vous appartient pas.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Création utilisateur
        |--------------------------------------------------------------------------
        */
        $user = User::create([
            'name' =>
                trim($validated['name']),

            'email' =>
                strtolower(
                    trim($validated['email'])
                ),

            'password' =>
                $validated['password'],

            'role' =>
                $validated['role'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Affectation automatique
        |--------------------------------------------------------------------------
        |
        | Exemple :
        |
        | Admin connecté
        |       ↓
        | LOVASOA / Collège
        |       ↓
        | Création Secrétaire
        |       ↓
        | establishment_user
        |       ↓
        | secrétaire + LOVASOA
        |
        |--------------------------------------------------------------------------
        */
        if (
            $validated['role'] !== UserRole::ADMIN->value &&
            $establishment
        ) {
            $user->assignedEstablishments()->sync([
                $establishment->id,
            ]);
        }

        $message = 'Utilisateur créé avec succès.';

        if (
            $validated['role'] !== UserRole::ADMIN->value &&
            $establishment
        ) {
            $message =
                'Utilisateur créé et automatiquement affecté à "' .
                $establishment->nom .
                '".';
        }

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                $message
            );
    }

    /**
     * Afficher un utilisateur.
     */
    public function show(User $user): View
    {
        $user->load([
            'assignedEstablishments.schoolType',
        ]);

        return view(
            'users.show',
            compact('user')
        );
    }

    /**
     * Formulaire modification.
     *
     * La modification conserve ici le système
     * d'affectation existant.
     */
    public function edit(User $user): View
    {
        $roles = UserRole::cases();

        $establishments = Establishment::query()
            ->with('schoolType')
            ->where('user_id', auth()->id())
            ->orderBy('nom')
            ->get();

        $assignedEstablishmentIds = $user
            ->assignedEstablishments()
            ->pluck('establishments.id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        return view(
            'users.edit',
            compact(
                'user',
                'roles',
                'establishments',
                'assignedEstablishmentIds'
            )
        );
    }

    /**
     * Mettre à jour.
     */
    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        $validRoles = array_map(
            fn (UserRole $role) => $role->value,
            UserRole::cases()
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in($validRoles),
            ],

            'establishment_ids' => [
                'nullable',
                'array',
            ],

            'establishment_ids.*' => [
                'integer',
                'distinct',
                Rule::exists(
                    'establishments',
                    'id'
                ),
            ],
        ], [
            'name.required' =>
                'Le nom est obligatoire.',

            'name.string' =>
                'Le nom est invalide.',

            'name.max' =>
                'Le nom ne doit pas dépasser 255 caractères.',

            'email.required' =>
                'L’adresse email est obligatoire.',

            'email.email' =>
                'Veuillez saisir une adresse email valide.',

            'email.max' =>
                'L’adresse email ne doit pas dépasser 255 caractères.',

            'email.unique' =>
                'Cette adresse email est déjà utilisée.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',

            'role.required' =>
                'Le rôle est obligatoire.',

            'role.in' =>
                'Le rôle sélectionné est invalide.',

            'establishment_ids.array' =>
                'La sélection des établissements est invalide.',

            'establishment_ids.*.exists' =>
                'Un des établissements sélectionnés est invalide.',

            'establishment_ids.*.distinct' =>
                'Un établissement a été sélectionné plusieurs fois.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Empêcher l'admin connecté de supprimer son propre rôle
        |--------------------------------------------------------------------------
        */
        if (
            $user->id === auth()->id() &&
            $validated['role'] !== UserRole::ADMIN->value
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' =>
                        'Vous ne pouvez pas retirer votre propre rôle administrateur.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Établissements sélectionnés
        |--------------------------------------------------------------------------
        */
        $establishmentIds = collect(
            $validated['establishment_ids'] ?? []
        )
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Vérifier que les établissements appartiennent
        | à l'administrateur connecté
        |--------------------------------------------------------------------------
        */
        if ($establishmentIds->isNotEmpty()) {
            $allowedCount = Establishment::query()
                ->where('user_id', auth()->id())
                ->whereIn('id', $establishmentIds)
                ->count();

            if (
                $allowedCount !==
                $establishmentIds->count()
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'establishment_ids' =>
                            'Un ou plusieurs établissements sélectionnés ne vous appartiennent pas.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Les utilisateurs non-admin doivent avoir
        | au moins un établissement
        |--------------------------------------------------------------------------
        */
        if (
            $validated['role'] !== UserRole::ADMIN->value &&
            $establishmentIds->isEmpty()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'establishment_ids' =>
                        'Veuillez sélectionner au moins un établissement pour cet utilisateur.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour informations
        |--------------------------------------------------------------------------
        */
        $user->name =
            trim($validated['name']);

        $user->email =
            strtolower(
                trim($validated['email'])
            );

        $user->role =
            $validated['role'];

        if (
            !empty(
                $validated['password']
            )
        ) {
            $user->password =
                $validated['password'];
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Synchronisation établissements
        |--------------------------------------------------------------------------
        */
        if (
            $validated['role'] ===
            UserRole::ADMIN->value
        ) {
            $user->assignedEstablishments()
                ->detach();
        } else {
            $user->assignedEstablishments()
                ->sync(
                    $establishmentIds->all()
                );
        }

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Utilisateur modifié avec succès.'
            );
    }

    /**
     * Supprimer.
     */
    public function destroy(
        User $user
    ): RedirectResponse {
        if ($user->id === auth()->id()) {
            return back()
                ->with(
                    'error',
                    'Vous ne pouvez pas supprimer votre propre compte.'
                );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Utilisateur supprimé avec succès.'
            );
    }
}
