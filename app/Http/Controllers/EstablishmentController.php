<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\SchoolType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    /**
     * Vérifier que l'utilisateur connecté est administrateur.
     *
     * Réservé aux opérations de gestion :
     * création, modification et suppression.
     */
    private function ensureAdmin(): void
    {
        abort_unless(
            auth()->check() &&
            auth()->user()->role === 'admin',
            403,
            'Accès réservé aux administrateurs.'
        );
    }

    /**
     * Vérifier que l'utilisateur est connecté.
     */
    private function ensureAuthenticated(): void
    {
        abort_unless(
            auth()->check(),
            403,
            'Vous devez être connecté.'
        );
    }

    /**
     * Retourner les établissements accessibles
     * à l'utilisateur connecté.
     *
     * ADMIN :
     *   établissements dont il est propriétaire.
     *
     * AUTRES UTILISATEURS :
     *   établissements affectés via establishment_user.
     */
    private function accessibleEstablishmentsQuery()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $user->establishments();
        }

        return $user->assignedEstablishments();
    }

    /**
     * Vérifier que l'utilisateur peut sélectionner
     * cet établissement.
     *
     * L'Admin peut sélectionner ses établissements.
     *
     * Le secrétariat et les autres utilisateurs autorisés
     * peuvent sélectionner les établissements qui leur
     * sont affectés via establishment_user.
     */
    private function authorizeSelection(
        Establishment $establishment
    ): void {
        $this->ensureAuthenticated();

        $hasAccess = $this->accessibleEstablishmentsQuery()
            ->whereKey($establishment->id)
            ->exists();

        abort_unless(
            $hasAccess,
            403,
            'Cet établissement ne vous est pas attribué.'
        );
    }

    /**
     * Vérifier qu'un établissement appartient
     * à l'administrateur connecté.
     *
     * Cette méthode reste volontairement stricte.
     *
     * Le secrétariat ne peut donc jamais modifier
     * ou supprimer un établissement.
     */
    private function authorizeEstablishment(
        Establishment $establishment
    ): void {
        $this->ensureAdmin();

        abort_unless(
            (int) $establishment->user_id ===
            (int) auth()->id(),
            403,
            'Cet établissement ne vous appartient pas.'
        );
    }

    /**
     * Liste des établissements accessibles.
     *
     * Accessible à l'administrateur et aux utilisateurs
     * ayant reçu une affectation.
     */
    public function index(): View
{
    $this->ensureAuthenticated();

    $establishments = $this->accessibleEstablishmentsQuery()
        ->with('schoolType')
        ->orderBy('id')
        ->get();

    $schoolTypes = SchoolType::query()
        ->orderBy('id')
        ->get();

    return view(
        'establishments.choose',
        compact(
            'establishments',
            'schoolTypes'
        )
    );
}
    /**
     * Page de sélection des établissements.
     *
     * Accessible à l'administrateur et aux utilisateurs
     * ayant reçu une affectation.
     */
    public function choose(): View
    {
        $this->ensureAuthenticated();

        $establishments = $this->accessibleEstablishmentsQuery()
            ->with('schoolType')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Types d'établissement
        |--------------------------------------------------------------------------
        | Nécessaires pour le formulaire de modification
        | affiché dans le modal de choose.blade.php.
        |--------------------------------------------------------------------------
        */
        $schoolTypes = SchoolType::query()
            ->orderBy('id')
            ->get();

        return view(
            'establishments.choose',
            compact(
                'establishments',
                'schoolTypes'
            )
        );
    }

    /**
     * Formulaire de création.
     *
     * Administrateur uniquement.
     */
    public function create(): View
    {
        $this->ensureAdmin();

        $schoolTypes = SchoolType::query()
            ->orderBy('id')
            ->get();

        return view(
            'establishments.create',
            compact('schoolTypes')
        );
    }

    /**
     * Créer un établissement.
     *
     * Administrateur uniquement.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        /*
        |--------------------------------------------------------------------------
        | Normalisation du téléphone avant validation
        |--------------------------------------------------------------------------
        */
        $request->merge([
            'telephone' => $this->normalizeTelephone(
                $request->input('telephone')
            ),
        ]);

        $validated = $request->validate([
            'school_type_id' => [
                'required',
                'integer',
                'exists:school_types,id',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'regex:/^0[2-9][0-9]{8}$/',
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'school_type_id.required' =>
                'Le type d’établissement est obligatoire.',

            'school_type_id.integer' =>
                'Le type d’établissement sélectionné est invalide.',

            'school_type_id.exists' =>
                'Le type d’établissement sélectionné est invalide.',

            'nom.required' =>
                'Le nom de l’établissement est obligatoire.',

            'nom.string' =>
                'Le nom de l’établissement est invalide.',

            'nom.max' =>
                'Le nom de l’établissement ne doit pas dépasser 255 caractères.',

            'adresse.string' =>
                'L’adresse est invalide.',

            'adresse.max' =>
                'L’adresse ne doit pas dépasser 255 caractères.',

            'telephone.regex' =>
                'Veuillez saisir un numéro de téléphone malgache valide. Exemple : 034 12 345 67.',

            'email.email' =>
                'Veuillez saisir une adresse email valide.',

            'email.regex' =>
                'Veuillez saisir une adresse email valide. Exemple : test@gmail.com.',

            'email.max' =>
                'L’adresse email ne doit pas dépasser 255 caractères.',

            'logo.image' =>
                'Le fichier du logo doit être une image valide.',

            'logo.mimes' =>
                'Le logo doit être au format JPG, JPEG, PNG ou WEBP.',

            'logo.max' =>
                'Le logo ne doit pas dépasser 5 Mo.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalisation email
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['email'])) {
            $validated['email'] = strtolower(
                trim($validated['email'])
            );
        } else {
            $validated['email'] = null;
        }

        $validated['telephone'] =
            $validated['telephone'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Upload du logo de l'établissement.
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request
                ->file('logo')
                ->store('establishments/logos', 'public');
        } else {
            $validated['logo'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | L'établissement appartient à l'administrateur connecté.
        |--------------------------------------------------------------------------
        */
        $validated['user_id'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */
        $establishment = Establishment::create(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Sélection automatique du nouvel établissement.
        |--------------------------------------------------------------------------
        */
        $this->setCurrentEstablishment(
            $establishment
        );

        /*
        |--------------------------------------------------------------------------
        | Réinitialiser l'année scolaire.
        |--------------------------------------------------------------------------
        */
        $this->resetSchoolYear();

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Établissement "' .
                $establishment->nom .
                '" créé et sélectionné avec succès.'
            );
    }

    /**
     * Sélectionner un établissement.
     *
     * Accessible à l'administrateur et aux utilisateurs
     * auxquels l'établissement a été affecté.
     */
    public function select(
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeSelection(
            $establishment
        );

        $this->setCurrentEstablishment(
            $establishment
        );

        $this->resetSchoolYear();

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Établissement "' .
                $establishment->nom .
                '" sélectionné avec succès.'
            );
    }

    /**
     * Modifier un établissement.
     *
     * Administrateur uniquement.
     */
    public function edit(
        Establishment $establishment
    ): View {
        $this->authorizeEstablishment(
            $establishment
        );

        $schoolTypes = SchoolType::query()
            ->orderBy('id')
            ->get();

        return view(
            'establishments.edit',
            compact(
                'establishment',
                'schoolTypes'
            )
        );
    }

    /**
     * Mettre à jour un établissement.
     *
     * Administrateur uniquement.
     */
    public function update(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment(
            $establishment
        );

        /*
        |--------------------------------------------------------------------------
        | Normalisation téléphone
        |--------------------------------------------------------------------------
        */
        $request->merge([
            'telephone' => $this->normalizeTelephone(
                $request->input('telephone')
            ),
        ]);

        $validated = $request->validate([
            'school_type_id' => [
                'required',
                'integer',
                'exists:school_types,id',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'regex:/^0[2-9][0-9]{8}$/',
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'school_type_id.required' =>
                'Le type d’établissement est obligatoire.',

            'school_type_id.exists' =>
                'Le type d’établissement sélectionné est invalide.',

            'nom.required' =>
                'Le nom de l’établissement est obligatoire.',

            'nom.max' =>
                'Le nom de l’établissement ne doit pas dépasser 255 caractères.',

            'telephone.regex' =>
                'Veuillez saisir un numéro de téléphone malgache valide. Exemple : 034 12 345 67.',

            'email.email' =>
                'Veuillez saisir une adresse email valide.',

            'email.regex' =>
                'Veuillez saisir une adresse email valide. Exemple : test@gmail.com.',

            'email.max' =>
                'L’adresse email ne doit pas dépasser 255 caractères.',

            'logo.image' =>
                'Le fichier du logo doit être une image valide.',

            'logo.mimes' =>
                'Le logo doit être au format JPG, JPEG, PNG ou WEBP.',

            'logo.max' =>
                'Le logo ne doit pas dépasser 5 Mo.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalisation email
        |--------------------------------------------------------------------------
        */
        $validated['email'] = !empty($validated['email'])
            ? strtolower(trim($validated['email']))
            : null;

        $validated['telephone'] =
            $validated['telephone'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Gestion du logo de l'établissement.
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('logo')) {
            $newLogo = $request
                ->file('logo')
                ->store('establishments/logos', 'public');

            /*
            |--------------------------------------------------------------------------
            | Supprimer l'ancien logo après l'enregistrement du nouveau.
            |--------------------------------------------------------------------------
            */
            if (!empty($establishment->logo)) {
                Storage::disk('public')->delete(
                    $establishment->logo
                );
            }

            $validated['logo'] = $newLogo;
        } else {
            /*
            |--------------------------------------------------------------------------
            | Aucun nouveau logo : conserver celui existant.
            |--------------------------------------------------------------------------
            */
            unset($validated['logo']);
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */
        $establishment->update(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Actualiser le nom dans la session.
        |--------------------------------------------------------------------------
        */
        if (
            (int) session('establishment_id') ===
            (int) $establishment->id
        ) {
            session([
                'establishment_name' =>
                    $establishment->nom,
            ]);
        }

        return redirect()
            ->route('establishments.index')
            ->with(
                'success',
                'Établissement modifié avec succès.'
            );
    }

    /**
     * Supprimer un établissement.
     *
     * Administrateur uniquement.
     */
    public function destroy(
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment(
            $establishment
        );

        if (
            (int) session('establishment_id') ===
            (int) $establishment->id
        ) {
            return back()->with(
                'error',
                'Vous ne pouvez pas supprimer l’établissement actuellement sélectionné. Sélectionnez d’abord un autre établissement.'
            );
        }

        $establishment->delete();

        return redirect()
            ->route('establishments.index')
            ->with(
                'success',
                'Établissement supprimé avec succès.'
            );
    }

    /**
     * Définir l'établissement courant.
     */
    private function setCurrentEstablishment(
        Establishment $establishment
    ): void {
        session([
            'establishment_id' =>
                $establishment->id,

            'establishment_name' =>
                $establishment->nom,
        ]);
    }

    /**
     * Réinitialiser l'année scolaire.
     */
    private function resetSchoolYear(): void
    {
        session()->forget([
            'school_year_id',
            'school_year_name',
        ]);
    }

    /**
     * Normaliser un numéro de téléphone malgache.
     */
    private function normalizeTelephone(
        mixed $telephone
    ): ?string {
        if (
            $telephone === null ||
            trim((string) $telephone) === ''
        ) {
            return null;
        }

        $telephone = trim((string) $telephone);

        /*
        |--------------------------------------------------------------------------
        | Supprimer espaces, tirets et parenthèses.
        |--------------------------------------------------------------------------
        */
        $telephone = preg_replace(
            '/[\s\-\(\)]/',
            '',
            $telephone
        );

        /*
        |--------------------------------------------------------------------------
        | +261XXXXXXXXX -> 0XXXXXXXXX
        |--------------------------------------------------------------------------
        */
        if (str_starts_with($telephone, '+261')) {
            $telephone = '0' .
                substr($telephone, 4);
        }

        /*
        |--------------------------------------------------------------------------
        | 261XXXXXXXXX -> 0XXXXXXXXX
        |--------------------------------------------------------------------------
        */
        elseif (str_starts_with($telephone, '261')) {
            $telephone = '0' .
                substr($telephone, 3);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier le format final.
        |--------------------------------------------------------------------------
        */
        if (
            !preg_match(
                '/^0[2-9][0-9]{8}$/',
                $telephone
            )
        ) {
            return $telephone;
        }

        return $telephone;
    }
}