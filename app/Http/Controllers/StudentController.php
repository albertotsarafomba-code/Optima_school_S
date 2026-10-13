<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Establishment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    /**
     * Retourne les établissements accessibles par l'utilisateur.
     */
    private function accessibleEstablishmentsQuery(): Builder
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Utilisateur non authentifié.');
        }

        if ($user->role === 'admin') {
            return $user->establishments()->getQuery();
        }

        return $user->assignedEstablishments()->getQuery();
    }

    /**
     * Récupère l'établissement actuellement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Utilisateur non authentifié.');
        }

        $establishmentId = (int) session('establishment_id');

        if ($establishmentId <= 0) {
            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Veuillez sélectionner un établissement.'
                )
                ->throwResponse();
        }

        $establishment = Establishment::query()
            ->whereKey($establishmentId)
            ->with('schoolType')
            ->first();

        if (!$establishment) {
            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            abort(
                404,
                'L’établissement sélectionné n’existe pas.'
            );
        }

        /*
         * Vérification des droits d'accès.
         */
        if ($user->role === 'admin') {
            $hasAccess = Establishment::query()
                ->whereKey($establishmentId)
                ->where('user_id', $user->id)
                ->exists();
        } else {
            $hasAccess = $user->assignedEstablishments()
                ->where(
                    'establishments.id',
                    $establishmentId
                )
                ->exists();
        }

        if (!$hasAccess) {
            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            abort(
                403,
                'Vous n’avez pas accès à cet établissement.'
            );
        }

        /*
         * Synchronisation de la session.
         */
        session([
            'establishment_id' => $establishment->id,
            'establishment_name' => $establishment->nom,
        ]);

        return $establishment;
    }

    /**
     * Détermine l'année scolaire active.
     *
     * Priorité :
     * 1. année scolaire de la session
     * 2. année active
     * 3. première année disponible
     */
    private function activeSchoolYear($schoolYears)
    {
        $activeSchoolYear = null;

        $schoolYearId = session('school_year_id');

        /*
         * 1. Année sélectionnée dans la session.
         */
        if ($schoolYearId) {
            $activeSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) $schoolYearId
            );
        }

        /*
         * 2. Année active.
         */
        if (!$activeSchoolYear) {
            $activeSchoolYear = $schoolYears->firstWhere(
                'is_active',
                true
            );
        }

        /*
         * 3. Première année disponible.
         */
        if (!$activeSchoolYear) {
            $activeSchoolYear = $schoolYears->first();
        }

        /*
         * Synchronisation de la session.
         */
        if ($activeSchoolYear) {
            session([
                'school_year_id' => $activeSchoolYear->id,
                'school_year_name' =>
                    $activeSchoolYear->name
                    ?? $activeSchoolYear->nom
                    ?? $activeSchoolYear->annee_scolaire
                    ?? null,
            ]);
        }

        return $activeSchoolYear;
    }

    /**
     * Génère automatiquement le prochain matricule.
     *
     * Format :
     *
     * MAT-2026-0001
     * MAT-2026-0002
     * MAT-2026-0003
     *
     * Le numéro est indépendant de l'ID étudiant.
     */
    private function generateMatricule(
        int $establishmentId
    ): string {
        $year = now()->year;

        $prefix = 'MAT-' . $year . '-';

        $lastStudent = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->whereNotNull('matricule')
            ->where(
                'matricule',
                'like',
                $prefix . '%'
            )
            ->orderByDesc('id')
            ->first();

        /*
         * Aucun matricule existant.
         */
        if (
            !$lastStudent ||
            !$lastStudent->matricule
        ) {
            return $prefix . '0001';
        }

        /*
         * Récupération du numéro après MAT-AAAA-
         */
        $lastNumber = (int) substr(
            $lastStudent->matricule,
            strlen($prefix)
        );

        $nextNumber = $lastNumber + 1;

        /*
         * Format sur 4 chiffres.
         */
        return $prefix . str_pad(
            $nextNumber,
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Affiche la liste des étudiants.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $establishmentId = $establishment->id;

        /*
         * Recherche.
         */
        $search = trim(
            $request->input('search', '')
        );

        /*
         * =========================================================
         * STATISTIQUES
         * =========================================================
         */

        $statisticsQuery = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        $studentCount = (clone $statisticsQuery)->count();

        $maleCount = (clone $statisticsQuery)
            ->where(function ($query) {
                $query
                    ->where('sexe', 'M')
                    ->orWhere('sexe', 'Masculin');
            })
            ->count();

        $femaleCount = (clone $statisticsQuery)
            ->where(function ($query) {
                $query
                    ->where('sexe', 'F')
                    ->orWhere('sexe', 'Féminin');
            })
            ->count();

        /*
         * Étudiants ayant une classe.
         */
        $studentsWithClass = 0;

        if (Schema::hasColumn('students', 'classe')) {
            $studentsWithClass = (clone $statisticsQuery)
                ->whereNotNull('classe')
                ->where('classe', '!=', '')
                ->count();
        }

        /*
         * Étudiants sans classe.
         */
        $studentsWithoutClass =
            $studentCount - $studentsWithClass;

        /*
         * =========================================================
         * LISTE DES ÉTUDIANTS
         * =========================================================
         */

        $students = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        /*
                         * Matricule.
                         */
                        if (
                            Schema::hasColumn(
                                'students',
                                'matricule'
                            )
                        ) {
                            $q->where(
                                'matricule',
                                'like',
                                '%' . $search . '%'
                            );
                        }

                        /*
                         * Nom.
                         */
                        $q->orWhere(
                            'nom',
                            'like',
                            '%' . $search . '%'
                        );

                        /*
                         * Prénom.
                         */
                        $q->orWhere(
                            'prenom',
                            'like',
                            '%' . $search . '%'
                        );

                        /*
                         * Classe.
                         */
                        if (
                            Schema::hasColumn(
                                'students',
                                'classe'
                            )
                        ) {
                            $q->orWhere(
                                'classe',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    });
                }
            )
            ->latest()
            ->get();

        /*
         * =========================================================
         * CLASSES
         * =========================================================
         */

        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom_classe')
            ->get();

        /*
         * =========================================================
         * STATISTIQUES
         * =========================================================
         */

        $statistics = [
            'total' => $studentCount,
            'masculins' => $maleCount,
            'feminins' => $femaleCount,
            'avec_classe' => $studentsWithClass,
            'sans_classe' => $studentsWithoutClass,
            'resultats' => $students->count(),
        ];

        return view(
            'students.index',
            compact(
                'students',
                'classes',
                'search',
                'establishment',
                'statistics'
            )
        );
    }

    /**
     * Formulaire de création d'un étudiant.
     */
    public function create()
    {
        $establishment = $this->currentEstablishment();

        $establishmentId = $establishment->id;

        /*
         * Classes de l'établissement courant.
         */
        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom_classe')
            ->get();

        /*
         * Années scolaires de l'établissement courant.
         */
        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        /*
         * Année scolaire active.
         */
        $activeSchoolYear = $this->activeSchoolYear(
            $schoolYears
        );

        /*
         * Prochain matricule automatique.
         */
        $nextMatricule = $this->generateMatricule(
            $establishmentId
        );

        return view(
            'students.create',
            compact(
                'establishment',
                'classes',
                'schoolYears',
                'activeSchoolYear',
                'nextMatricule'
            )
        );
    }

    /**
     * Enregistre un nouvel étudiant.
     */
    public function store(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $establishmentId = $establishment->id;

        /*
         * Validation dynamique du matricule.
         */
        $rules = [
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

            'sexe' => [
                'required',
                'in:M,F,Masculin,Féminin',
            ],

            'date_naissance' => [
                'nullable',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:500',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50',
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

            'class_room_id' => [
                'nullable',
                'integer',
            ],

            'school_year_id' => [
                'nullable',
                'integer',
            ],
        ];

        /*
         * Le matricule reste géré automatiquement.
         */
        if (Schema::hasColumn('students', 'matricule')) {
            $rules['matricule'] = [
                'nullable',
                'string',
                'max:100',
            ];
        }

        $validated = $request->validate($rules);

        /*
         * Vérification de la classe.
         */
        if (
            !empty($validated['class_room_id'])
        ) {
            $classExists = ClassRoom::query()
                ->whereKey(
                    $validated['class_room_id']
                )
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->exists();

            if (!$classExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'class_room_id' =>
                            'La classe sélectionnée n’appartient pas à cet établissement.',
                    ]);
            }
        }

        /*
         * Vérification de l'année scolaire.
         */
        if (
            !empty($validated['school_year_id'])
        ) {
            $schoolYearExists = SchoolYear::query()
                ->whereKey(
                    $validated['school_year_id']
                )
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->exists();

            if (!$schoolYearExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'school_year_id' =>
                            'L’année scolaire sélectionnée n’appartient pas à cet établissement.',
                    ]);
            }
        }

        /*
         * Toujours forcer l'établissement courant.
         */
        $validated['establishment_id'] =
            $establishmentId;

        /*
         * =========================================================
         * MATRICULE AUTOMATIQUE
         * =========================================================
         *
         * Le matricule envoyé par le navigateur est ignoré.
         * Le backend génère toujours le prochain matricule.
         */
        if (Schema::hasColumn('students', 'matricule')) {
            $validated['matricule'] =
                $this->generateMatricule(
                    $establishmentId
                );
        }

        /*
         * Création.
         */
        $student = Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Étudiant ajouté avec succès.'
            );
    }

    /**
     * Affiche un étudiant.
     */
    public function show(Student $student)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité :
         * l'étudiant doit appartenir à l'établissement courant.
         */
        if (
            (int) $student->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Vous n’avez pas accès à cet étudiant.'
            );
        }

        return view(
            'students.show',
            compact(
                'student',
                'establishment'
            )
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Student $student)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité établissement.
         */
        if (
            (int) $student->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Vous n’avez pas accès à cet étudiant.'
            );
        }

        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderBy('nom_classe')
            ->get();

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        /*
         * Année scolaire active.
         */
        $activeSchoolYear = $this->activeSchoolYear(
            $schoolYears
        );

        return view(
            'students.edit',
            compact(
                'student',
                'establishment',
                'classes',
                'schoolYears',
                'activeSchoolYear'
            )
        );
    }

    /**
     * Met à jour un étudiant.
     */
    public function update(
        Request $request,
        Student $student
    ) {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité établissement.
         */
        if (
            (int) $student->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Vous n’avez pas accès à cet étudiant.'
            );
        }

        $rules = [
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

            'sexe' => [
                'required',
                'in:M,F,Masculin,Féminin',
            ],

            'date_naissance' => [
                'nullable',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:500',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50',
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

            'class_room_id' => [
                'nullable',
                'integer',
            ],

            'school_year_id' => [
                'nullable',
                'integer',
            ],
        ];

        /*
         * Le matricule existant ne doit pas être modifié
         * automatiquement lors de l'édition.
         */
        if (Schema::hasColumn('students', 'matricule')) {
            $rules['matricule'] = [
                'nullable',
                'string',
                'max:100',
                'unique:students,matricule,' .
                    $student->id,
            ];
        }

        $validated = $request->validate($rules);

        /*
         * Vérification de la classe.
         */
        if (
            !empty($validated['class_room_id'])
        ) {
            $classExists = ClassRoom::query()
                ->whereKey(
                    $validated['class_room_id']
                )
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->exists();

            if (!$classExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'class_room_id' =>
                            'La classe sélectionnée n’appartient pas à cet établissement.',
                    ]);
            }
        }

        /*
         * Vérification de l'année scolaire.
         */
        if (
            !empty($validated['school_year_id'])
        ) {
            $schoolYearExists = SchoolYear::query()
                ->whereKey(
                    $validated['school_year_id']
                )
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->exists();

            if (!$schoolYearExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'school_year_id' =>
                            'L’année scolaire sélectionnée n’appartient pas à cet établissement.',
                    ]);
            }
        }

        /*
         * Ne jamais permettre de changer
         * l'établissement depuis le formulaire.
         */
        unset(
            $validated['establishment_id']
        );

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Étudiant modifié avec succès.'
            );
    }

    /**
     * Supprime un étudiant.
     */
    public function destroy(Student $student)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité établissement.
         */
        if (
            (int) $student->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Vous n’avez pas accès à cet étudiant.'
            );
        }

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Étudiant supprimé avec succès.'
            );
    }
}