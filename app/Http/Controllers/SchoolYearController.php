<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolYearController extends Controller
{
    /**
     * Vérifier qu'un utilisateur est connecté.
     */
    private function user()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Utilisateur non connecté.');
        }

        return $user;
    }

    /**
     * Vérifier qu'un utilisateur possède réellement
     * un établissement accessible.
     *
     * ADMIN :
     *   établissements dont il est propriétaire.
     *
     * AUTRES :
     *   établissements affectés via establishment_user.
     */
    private function accessibleEstablishmentsQuery()
    {
        $user = $this->user();

        if ($user->role === 'admin') {
            return $user->establishments();
        }

        return $user->assignedEstablishments();
    }

    /**
     * Récupérer l'établissement actuellement sélectionné.
     *
     * Cette méthode ne fait jamais confiance uniquement
     * à la session : elle vérifie que l'utilisateur possède
     * réellement l'accès à l'établissement.
     */
    private function establishmentId(): int
    {
        $user = $this->user();

        $id = session('establishment_id');

        /*
        |--------------------------------------------------------------------------
        | Aucun établissement dans la session
        |--------------------------------------------------------------------------
        */
        if (!$id) {

            $establishments = $this
                ->accessibleEstablishmentsQuery()
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Aucun établissement accessible
            |--------------------------------------------------------------------------
            */
            if ($establishments->isEmpty()) {

                session()->forget([
                    'establishment_id',
                    'establishment_name',
                    'school_year_id',
                    'school_year_name',
                ]);

                if ($user->role === 'admin') {

                    redirect()
                        ->route('establishments.create')
                        ->send();
                }

                redirect()
                    ->route('establishments.choose')
                    ->with(
                        'error',
                        'Aucun établissement ne vous a été affecté. Contactez l’administrateur.'
                    )
                    ->send();

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | Un seul établissement accessible
            |--------------------------------------------------------------------------
            */
            if ($establishments->count() === 1) {

                $establishment = $establishments->first();

                $id = $establishment->id;

                session([
                    'establishment_id' => $id,
                    'establishment_name' => $establishment->nom,
                ]);

                return (int) $id;
            }

            /*
            |--------------------------------------------------------------------------
            | Plusieurs établissements
            |--------------------------------------------------------------------------
            */
            redirect()
                ->route('establishments.choose')
                ->send();

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'établissement de la session
        | est réellement accessible par l'utilisateur.
        |--------------------------------------------------------------------------
        */
        $exists = $this
            ->accessibleEstablishmentsQuery()
            ->whereKey($id)
            ->exists();

        if (!$exists) {

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Vous n’avez pas accès à cet établissement.'
                )
                ->send();

            exit;
        }

        return (int) $id;
    }

    /**
     * Vérifier qu'une action d'administration
     * est effectuée uniquement par un administrateur.
     */
    private function ensureAdmin(): void
    {
        $user = $this->user();

        abort_unless(
            $user->role === 'admin',
            403,
            'La gestion des années scolaires est réservée aux administrateurs.'
        );
    }

    /**
     * Vérifier qu'une année scolaire appartient
     * à l'établissement actuellement sélectionné.
     */
    private function authorizeSchoolYear(
        SchoolYear $schoolYear
    ): void {
        $establishmentId = $this->establishmentId();

        abort_unless(
            (int) $schoolYear->establishment_id === $establishmentId,
            403,
            'Cette année scolaire n’appartient pas à l’établissement sélectionné.'
        );
    }

    /**
     * Liste des années scolaires.
     *
     * Accessible aux utilisateurs autorisés
     * de l'établissement courant.
     */
    public function index()
    {
        $establishmentId = $this->establishmentId();

        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishmentId
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année sélectionnée dans la session
        |--------------------------------------------------------------------------
        */
        $selectedSchoolYearId = session('school_year_id');

        $selectedSchoolYear = null;

        if ($selectedSchoolYearId) {

            $selectedSchoolYear = $schoolYears
                ->firstWhere(
                    'id',
                    (int) $selectedSchoolYearId
                );

            /*
            |--------------------------------------------------------------------------
            | L'année de session n'appartient plus
            | à l'établissement courant
            |--------------------------------------------------------------------------
            */
            if (!$selectedSchoolYear) {

                session()->forget([
                    'school_year_id',
                    'school_year_name',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Si aucune année n'est sélectionnée,
        | prendre automatiquement l'année active.
        |--------------------------------------------------------------------------
        */
        if (!$selectedSchoolYear) {

            $selectedSchoolYear = $schoolYears
                ->firstWhere('is_active', true);

            if ($selectedSchoolYear) {

                session([
                    'school_year_id' =>
                        $selectedSchoolYear->id,

                    'school_year_name' =>
                        $selectedSchoolYear->name,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Récupérer l'année réellement active
        |--------------------------------------------------------------------------
        */
        $activeSchoolYear = $schoolYears
            ->firstWhere('is_active', true);

        return view(
            'school_years.index',
            compact(
                'schoolYears',
                'selectedSchoolYear',
                'activeSchoolYear'
            )
        );
    }

    /**
     * Formulaire de création.
     *
     * ADMIN UNIQUEMENT.
     */
    public function create()
    {
        $this->ensureAdmin();

        $this->establishmentId();

        return view('school_years.create');
    }

    /**
     * Enregistrer une nouvelle année scolaire.
     *
     * ADMIN UNIQUEMENT.
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $establishmentId = $this->establishmentId();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'school_years',
                    'name'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'establishment_id',
                            $establishmentId
                        )
                ),
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ], [

            'name.required' =>
                'Le nom de l’année scolaire est obligatoire.',

            'name.unique' =>
                'Cette année scolaire existe déjà pour cet établissement.',

            'start_date.required' =>
                'La date de début est obligatoire.',

            'end_date.required' =>
                'La date de fin est obligatoire.',

            'end_date.after' =>
                'La date de fin doit être après la date de début.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Si cette année devient active,
        | désactiver les autres.
        |--------------------------------------------------------------------------
        */
        if ($request->boolean('is_active')) {

            SchoolYear::where(
                'establishment_id',
                $establishmentId
            )->update([
                'is_active' => false,
            ]);
        }

        $validated['establishment_id'] = $establishmentId;

        $validated['is_active'] =
            $request->boolean('is_active');

        $schoolYear = SchoolYear::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Si elle est active,
        | elle devient l'année sélectionnée.
        |--------------------------------------------------------------------------
        */
        if ($schoolYear->is_active) {

            session([
                'school_year_id' =>
                    $schoolYear->id,

                'school_year_name' =>
                    $schoolYear->name,
            ]);
        }

        return redirect()
            ->route('school_years.index')
            ->with(
                'success',
                'Année scolaire ajoutée avec succès.'
            );
    }

    /**
     * Afficher une année scolaire.
     *
     * Accessible aux utilisateurs autorisés.
     */
    public function show(SchoolYear $schoolYear)
    {
        $this->authorizeSchoolYear($schoolYear);

        return view(
            'school_years.show',
            compact('schoolYear')
        );
    }

    /**
     * Formulaire de modification.
     *
     * ADMIN UNIQUEMENT.
     */
    public function edit(SchoolYear $schoolYear)
    {
        $this->ensureAdmin();

        $this->authorizeSchoolYear($schoolYear);

        return view(
            'school_years.edit',
            compact('schoolYear')
        );
    }

    /**
     * Modifier une année scolaire.
     *
     * ADMIN UNIQUEMENT.
     */
    public function update(
        Request $request,
        SchoolYear $schoolYear
    ) {
        $this->ensureAdmin();

        $this->authorizeSchoolYear($schoolYear);

        $establishmentId = $this->establishmentId();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'school_years',
                    'name'
                )
                    ->ignore($schoolYear->id)
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'establishment_id',
                                $establishmentId
                            )
                    ),
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ], [

            'name.required' =>
                'Le nom de l’année scolaire est obligatoire.',

            'name.unique' =>
                'Cette année scolaire existe déjà pour cet établissement.',

            'start_date.required' =>
                'La date de début est obligatoire.',

            'end_date.required' =>
                'La date de fin est obligatoire.',

            'end_date.after' =>
                'La date de fin doit être après la date de début.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Si cette année devient active,
        | désactiver les autres.
        |--------------------------------------------------------------------------
        */
        if ($request->boolean('is_active')) {

            SchoolYear::where(
                'establishment_id',
                $establishmentId
            )
                ->where(
                    'id',
                    '!=',
                    $schoolYear->id
                )
                ->update([
                    'is_active' => false,
                ]);
        }

        $validated['is_active'] =
            $request->boolean('is_active');

        $schoolYear->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Si elle est active,
        | elle devient l'année sélectionnée.
        |--------------------------------------------------------------------------
        */
        if ($schoolYear->is_active) {

            session([
                'school_year_id' =>
                    $schoolYear->id,

                'school_year_name' =>
                    $schoolYear->name,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Si elle n'est plus active mais était sélectionnée,
        | on garde la sélection car elle peut être consultée.
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('school_years.index')
            ->with(
                'success',
                'Année scolaire modifiée avec succès.'
            );
    }

    /**
     * Sélectionner une année scolaire.
     *
     * Accessible aux utilisateurs autorisés.
     *
     * Cette action ne change PAS le statut is_active.
     */
    public function select(SchoolYear $schoolYear)
    {
        $this->authorizeSchoolYear($schoolYear);

        session([
            'school_year_id' =>
                $schoolYear->id,

            'school_year_name' =>
                $schoolYear->name,
        ]);

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                "L'année scolaire {$schoolYear->name} est maintenant sélectionnée."
            );
    }

    /**
     * Activer une année scolaire.
     *
     * ADMIN UNIQUEMENT.
     */
    public function activate(SchoolYear $schoolYear)
    {
        $this->ensureAdmin();

        $this->authorizeSchoolYear($schoolYear);

        $establishmentId = $this->establishmentId();

        /*
        |--------------------------------------------------------------------------
        | Désactiver toutes les années de l'établissement.
        |--------------------------------------------------------------------------
        */
        SchoolYear::where(
            'establishment_id',
            $establishmentId
        )->update([
            'is_active' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activer l'année sélectionnée.
        |--------------------------------------------------------------------------
        */
        $schoolYear->update([
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | L'année activée devient également
        | l'année sélectionnée.
        |--------------------------------------------------------------------------
        */
        session([
            'school_year_id' =>
                $schoolYear->id,

            'school_year_name' =>
                $schoolYear->name,
        ]);

        return redirect()
            ->route('school_years.index')
            ->with(
                'success',
                'L’année scolaire "' .
                $schoolYear->name .
                '" est maintenant active.'
            );
    }

    /**
     * Supprimer une année scolaire.
     *
     * ADMIN UNIQUEMENT.
     */
    public function destroy(SchoolYear $schoolYear)
    {
        $this->ensureAdmin();

        $this->authorizeSchoolYear($schoolYear);

        /*
        |--------------------------------------------------------------------------
        | Ne pas supprimer l'année active.
        |--------------------------------------------------------------------------
        */
        if ($schoolYear->is_active) {

            return redirect()
                ->route('school_years.index')
                ->with(
                    'error',
                    'Impossible de supprimer l’année scolaire active.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Ne pas supprimer une année possédant des classes.
        |--------------------------------------------------------------------------
        */
        if ($schoolYear->classRooms()->exists()) {

            return redirect()
                ->route('school_years.index')
                ->with(
                    'error',
                    'Impossible de supprimer cette année scolaire car elle possède déjà des classes.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Ne pas supprimer une année possédant des inscriptions.
        |--------------------------------------------------------------------------
        */
        if ($schoolYear->inscriptions()->exists()) {

            return redirect()
                ->route('school_years.index')
                ->with(
                    'error',
                    'Impossible de supprimer cette année scolaire car elle possède déjà des inscriptions.'
                );
        }

        $schoolYearId = $schoolYear->id;

        $schoolYear->delete();

        /*
        |--------------------------------------------------------------------------
        | Nettoyer la session si cette année était sélectionnée.
        |--------------------------------------------------------------------------
        */
        if (
            (int) session('school_year_id') ===
            (int) $schoolYearId
        ) {

            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);
        }

        return redirect()
            ->route('school_years.index')
            ->with(
                'success',
                'Année scolaire supprimée avec succès.'
            );
    }
}