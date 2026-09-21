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
     *
     * IMPORTANT :
     * On utilise directement l'ID présent dans la session
     * pour récupérer le véritable établissement en base.
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

        /*
         * Récupération directe par l'ID réel de l'établissement.
         */
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
         *
         * ADMIN :
         * l'établissement doit appartenir à l'utilisateur.
         *
         * AUTRES UTILISATEURS :
         * l'établissement doit être présent dans establishment_user.
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
         * Synchronisation du nom de l'établissement
         * dans la session.
         */
        session([
            'establishment_id' => $establishment->id,
            'establishment_name' => $establishment->nom,
        ]);

        return $establishment;
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

        /*
         * Total étudiants.
         */
        $studentCount = (clone $statisticsQuery)->count();

        /*
         * Masculins.
         */
        $maleCount = (clone $statisticsQuery)
            ->where(function ($query) {
                $query
                    ->where('sexe', 'M')
                    ->orWhere('sexe', 'Masculin');
            })
            ->count();

        /*
         * Féminins.
         */
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
         * STATISTIQUES ENVOYÉES À LA VUE
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

        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom_classe')
            ->get();

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'students.create',
            compact(
                'establishment',
                'classes',
                'schoolYears'
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

        if (Schema::hasColumn('students', 'matricule')) {
            $rules['matricule'] = [
                'nullable',
                'string',
                'max:100',
                'unique:students,matricule',
            ];
        }

        $validated = $request->validate($rules);

        /*
         * Vérification de la classe si elle est fournie.
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
            ->orderByDesc('id')
            ->get();

        return view(
            'students.edit',
            compact(
                'student',
                'establishment',
                'classes',
                'schoolYears'
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
