<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\SchoolType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    /**
     * Vérifier que l'utilisateur connecté est administrateur.
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
     * Vérifier que l'établissement appartient
     * à l'administrateur connecté.
     */
    private function authorizeEstablishment(
        Establishment $establishment
    ): void {
        $this->ensureAdmin();

        abort_unless(
            (int) $establishment->user_id === (int) auth()->id(),
            403,
            'Cet établissement ne vous appartient pas.'
        );
    }

    /**
     * Liste des établissements de l'administrateur.
     */
    public function index(): View
    {
        $this->ensureAdmin();

        $establishments = auth()->user()
            ->establishments()
            ->with('schoolType')
            ->orderBy('id')
            ->get();

        return view(
            'establishments.choose',
            compact('establishments')
        );
    }

    /**
     * Afficher la page de sélection.
     *
     * L'administrateur peut avoir plusieurs établissements.
     */
    public function choose(): View
    {
        $this->ensureAdmin();

        $establishments = auth()->user()
            ->establishments()
            ->with('schoolType')
            ->orderBy('id')
            ->get();

        return view(
            'establishments.choose',
            compact('establishments')
        );
    }

    /**
     * Afficher le formulaire de création.
     *
     * Un administrateur peut créer plusieurs établissements.
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
     * Enregistrer un nouvel établissement.
     *
     * Chaque nouvel établissement appartient
     * à l'administrateur connecté.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $this->ensureAdmin();

        $validated = $request->validate([
            'school_type_id' => [
                'required',
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
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
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

            'email.email' =>
                'Veuillez saisir une adresse e-mail valide.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Association avec l'administrateur connecté
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Création du nouvel établissement
        |--------------------------------------------------------------------------
        */

        $establishment = Establishment::create(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Le nouvel établissement devient
        | automatiquement l'établissement courant
        |--------------------------------------------------------------------------
        */

        $this->setCurrentEstablishment(
            $establishment
        );

        /*
        |--------------------------------------------------------------------------
        | Réinitialiser l'année scolaire
        |--------------------------------------------------------------------------
        |
        | Très important en multi-établissement :
        | l'année scolaire précédente peut appartenir
        | à l'ancien établissement.
        |
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
     */
    public function select(
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment(
            $establishment
        );

        /*
        |--------------------------------------------------------------------------
        | Définir l'établissement courant
        |--------------------------------------------------------------------------
        */

        $this->setCurrentEstablishment(
            $establishment
        );

        /*
        |--------------------------------------------------------------------------
        | Réinitialiser l'année scolaire
        |--------------------------------------------------------------------------
        */

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
     * Conservé ici pour éviter de casser
     * les routes resource existantes.
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
     */
    public function update(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment(
            $establishment
        );

        $validated = $request->validate([
            'school_type_id' => [
                'required',
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
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
        ]);

        $establishment->update(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Si l'établissement modifié est celui
        | actuellement sélectionné, mettre à jour
        | également le nom en session.
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
     */
    public function destroy(
        Establishment $establishment
    ): RedirectResponse {
        $this->authorizeEstablishment(
            $establishment
        );

        /*
        |--------------------------------------------------------------------------
        | Empêcher la suppression de l'établissement
        | actuellement sélectionné.
        |--------------------------------------------------------------------------
        */

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
     * Définir l'établissement courant dans la session.
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
     * Réinitialiser le contexte année scolaire.
     */
    private function resetSchoolYear(): void
    {
        session()->forget([
            'school_year_id',
            'school_year_name',
        ]);
    }
}