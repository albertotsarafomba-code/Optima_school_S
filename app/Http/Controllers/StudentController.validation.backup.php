<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Afficher la liste des étudiants
     * appartenant uniquement à l'établissement sélectionné.
     */
    public function index(Request $request)
    {
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            return redirect()
                ->route('establishments.choose')
                ->with('error', 'Veuillez sélectionner un établissement.');
        }

        /*
         * Recherche
         */
        $search = trim($request->input('search', ''));

        /*
         * Étudiants de l'établissement sélectionné uniquement.
         */
        $students = Student::where(
            'establishment_id',
            $establishmentId
        )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where(
                        'matricule',
                        'like',
                        '%' . $search . '%'
                    )
                        ->orWhere(
                            'nom',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'prenom',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'classe',
                            'like',
                            '%' . $search . '%'
                        );
                });
            })
            ->latest()
            ->get();

        /*
         * Classes de l'établissement sélectionné.
         */
        $classes = ClassRoom::where(
            'establishment_id',
            $establishmentId
        )
            ->orderBy('nom_classe')
            ->get();

        return view('students.index', compact(
            'students',
            'classes',
            'search'
        ));
    }

    /**
     * Formulaire d'ajout d'un étudiant.
     */
    public function create()
    {
        $establishmentId = session('establishment_id');

        /*
         * Vérifier qu'un établissement est sélectionné.
         */
        if (!$establishmentId) {
            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Veuillez sélectionner un établissement.'
                );
        }

        /*
         * ============================================================
         * ANNÉES SCOLAIRES
         * ============================================================
         *
         * On récupère uniquement les années scolaires
         * appartenant à l'établissement sélectionné.
         */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishmentId
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        /*
         * ============================================================
         * ANNÉE SCOLAIRE ACTIVE
         * ============================================================
         *
         * Priorité :
         * 1. année présente dans la session
         * 2. année marquée comme active
         * 3. première année disponible
         */
        $activeSchoolYear = null;

        /*
         * Année enregistrée dans la session.
         */
        $sessionSchoolYearId = session('school_year_id');

        if ($sessionSchoolYearId) {
            $activeSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) $sessionSchoolYearId
            );
        }

        /*
         * Si aucune année de session n'est trouvée,
         * chercher l'année active.
         */
        if (!$activeSchoolYear) {
            $activeSchoolYear = $schoolYears->firstWhere(
                'is_active',
                true
            );
        }

        /*
         * Si aucune année n'est active,
         * utiliser la première année disponible.
         */
        if (!$activeSchoolYear) {
            $activeSchoolYear = $schoolYears->first();
        }

        /*
         * ============================================================
         * CLASSES
         * ============================================================
         *
         * Les classes appartiennent à l'établissement sélectionné.
         *
         * Si une année scolaire est disponible,
         * on filtre également par cette année.
         */
        $classesQuery = ClassRoom::where(
            'establishment_id',
            $establishmentId
        );

        if ($activeSchoolYear) {
            $classesQuery->where(
                'school_year_id',
                $activeSchoolYear->id
            );
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        /*
         * ============================================================
         * MATIÈRES
         * ============================================================
         *
         * Les matières appartiennent à :
         * - l'établissement sélectionné
         * - l'année scolaire sélectionnée
         *
         * On initialise avec une collection vide pour éviter
         * l'erreur Undefined variable $subjects.
         */
        $subjects = collect();

        if ($activeSchoolYear) {
            $subjects = Subject::where(
                'establishment_id',
                $establishmentId
            )
                ->where(
                    'school_year_id',
                    $activeSchoolYear->id
                )
                ->orderBy('nom')
                ->get();
        }

        /*
         * Envoyer toutes les variables nécessaires
         * à students/create.blade.php.
         */
        return view('students.create', compact(
            'classes',
            'schoolYears',
            'activeSchoolYear',
            'subjects'
        ));
    }

    /**
     * Enregistrer un nouvel étudiant.
     */
    public function store(Request $request)
    {
        $establishmentId = session('establishment_id');

        /*
         * Vérifier l'établissement sélectionné.
         */
        if (!$establishmentId) {
            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Veuillez sélectionner un établissement.'
                );
        }

        /*
         * Validation des données étudiant.
         */
        $validated = $request->validate([
            'matricule' => [
                'nullable',
                'string',
                'max:100',
                'unique:students,matricule',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'prenom' => [
                'required',
                'string',
                'max:255',
            ],

            'date_naissance' => [
                'required',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sexe' => [
                'required',
                'in:Masculin,Féminin',
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

            'classe' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
         * L'établissement est toujours imposé depuis la session.
         *
         * On ne fait jamais confiance à un establishment_id
         * envoyé depuis le formulaire.
         */
        $validated['establishment_id'] = $establishmentId;

        /*
         * Création de l'étudiant.
         */
        Student::create($validated);

        /*
         * Retour à la liste.
         */
        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Étudiant ajouté avec succès.'
            );
    }

    /**
     * Afficher les détails d'un étudiant.
     */
    public function show(Student $student)
    {
        /*
         * Vérifier l'appartenance à l'établissement.
         */
        $this->verifyEstablishment($student);

        return view(
            'students.show',
            compact('student')
        );
    }

    /**
     * Formulaire de modification d'un étudiant.
     */
    public function edit(Student $student)
    {
        /*
         * Vérifier l'établissement.
         */
        $this->verifyEstablishment($student);

        $establishmentId = session('establishment_id');

        /*
         * Classes de l'établissement sélectionné.
         */
        $classes = ClassRoom::where(
            'establishment_id',
            $establishmentId
        )
            ->orderBy('nom_classe')
            ->get();

        return view(
            'students.edit',
            compact(
                'student',
                'classes'
            )
        );
    }

    /**
     * Mettre à jour un étudiant.
     */
    public function update(
        Request $request,
        Student $student
    ) {
        /*
         * Vérifier l'établissement.
         */
        $this->verifyEstablishment($student);

        /*
         * Validation.
         */
        $validated = $request->validate([
            'matricule' => [
                'nullable',
                'string',
                'max:100',
                'unique:students,matricule,' . $student->id,
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'prenom' => [
                'required',
                'string',
                'max:255',
            ],

            'date_naissance' => [
                'required',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sexe' => [
                'required',
                'in:Masculin,Féminin',
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

            'classe' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
         * Mise à jour.
         */
        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Étudiant modifié avec succès.'
            );
    }

    /**
     * Supprimer un étudiant.
     */
    public function destroy(Student $student)
    {
        /*
         * Vérifier l'établissement.
         */
        $this->verifyEstablishment($student);

        /*
         * Suppression.
         */
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Étudiant supprimé avec succès.'
            );
    }

    /**
     * Vérifier qu'un étudiant appartient
     * à l'établissement actuellement sélectionné.
     */
    private function verifyEstablishment(
        Student $student
    ): void {
        $establishmentId = session('establishment_id');

        /*
         * Aucun établissement sélectionné.
         */
        if (!$establishmentId) {
            abort(
                403,
                'Aucun établissement sélectionné.'
            );
        }

        /*
         * L'étudiant appartient à un autre établissement.
         */
        if (
            (int) $student->establishment_id
            !== (int) $establishmentId
        ) {
            abort(
                403,
                'Cet étudiant n\'appartient pas à l\'établissement sélectionné.'
            );
        }
    }
}
