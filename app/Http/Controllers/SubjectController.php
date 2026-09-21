<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Récupère l'établissement actuellement sélectionné.
     */
    private function establishment(): Establishment
    {
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(
                403,
                'Aucun établissement n’est actuellement sélectionné.'
            );
        }

        return Establishment::findOrFail($establishmentId);
    }

    /**
     * Vérifie que l'utilisateur est administrateur.
     */
    private function ensureAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403,
            'Accès réservé aux administrateurs.'
        );
    }

    /**
     * Récupère l'année scolaire actuellement sélectionnée.
     *
     * Si une année est présente en session, elle est utilisée
     * uniquement si elle appartient bien à l'établissement courant.
     *
     * Sinon, on utilise l'année active.
     */
    private function activeSchoolYear(): ?SchoolYear
    {
        $establishment = $this->establishment();

        $schoolYearId = session('school_year_id');

        if ($schoolYearId) {
            $schoolYear = SchoolYear::query()
                ->where('id', $schoolYearId)
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->first();

            if ($schoolYear) {
                return $schoolYear;
            }
        }

        return SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->first();
    }

    /**
     * Vérifie qu'une matière appartient
     * à l'établissement courant.
     */
    private function authorizeSubject(Subject $subject): void
    {
        $establishment = $this->establishment();

        abort_unless(
            (int) $subject->establishment_id ===
            (int) $establishment->id,
            403,
            'Cette matière n’appartient pas à votre établissement.'
        );
    }

    /**
     * Vérifie qu'une matière appartient
     * à l'établissement et à l'année scolaire courants.
     */
    private function authorizeSubjectForCurrentYear(
        Subject $subject
    ): void {
        $this->authorizeSubject($subject);

        $schoolYear = $this->activeSchoolYear();

        abort_unless(
            $schoolYear &&
            (int) $subject->school_year_id ===
            (int) $schoolYear->id,
            403,
            'Cette matière n’appartient pas à l’année scolaire courante.'
        );
    }

    /**
     * Liste des matières de l'établissement
     * pour l'année scolaire courante.
     */
    public function index()
    {
        $this->ensureAdmin();

        $establishment = $this->establishment();
        $schoolYear = $this->activeSchoolYear();

        $subjects = collect();

        if ($schoolYear) {
            $subjects = Subject::query()
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->where(
                    'school_year_id',
                    $schoolYear->id
                )
                ->with([
                    'schoolYear',
                ])
                ->withCount([
                    'teacherSubjects',
                ])
                ->orderBy('nom')
                ->get();
        }

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        return view(
            'subjects.index',
            compact(
                'subjects',
                'establishment',
                'schoolYear',
                'schoolYears'
            )
        );
    }

    /**
     * Formulaire d'ajout d'une matière.
     */
    public function create()
    {
        $this->ensureAdmin();

        $establishment = $this->establishment();

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $activeSchoolYear = $this->activeSchoolYear();

        return view(
            'subjects.create',
            compact(
                'establishment',
                'schoolYears',
                'activeSchoolYear'
            )
        );
    }

    /**
     * Enregistre une nouvelle matière.
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $establishment = $this->establishment();

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                Rule::exists('school_years', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'establishment_id',
                                $establishment->id
                            )
                    ),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $nom = trim($validated['nom']);

        /*
         * Vérifie si la matière existe déjà
         * dans le même établissement et la même année.
         */
        $exists = Subject::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->whereRaw(
                'LOWER(nom) = ?',
                [mb_strtolower($nom)]
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'nom' =>
                        'Cette matière existe déjà pour cette année scolaire.',
                ]);
        }

        Subject::create([
            'establishment_id' => $establishment->id,
            'school_year_id' => $validated['school_year_id'],
            'school_type_id' => $establishment->school_type_id,
            'code' => isset($validated['code'])
                ? trim($validated['code'])
                : null,
            'nom' => $nom,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Matière ajoutée avec succès.'
            );
    }

    /**
     * Affichage détaillé d'une matière.
     */
    public function show(Subject $subject)
    {
        $this->ensureAdmin();

        $this->authorizeSubjectForCurrentYear($subject);

        /*
         * On charge les affectations complètes :
         *
         * matière
         *   ├── enseignant
         *   ├── classe
         *   └── année scolaire
         *
         * Cela permet d'afficher correctement les affectations
         * sans dépendre uniquement du pivot teachers().
         */
        $subject->load([
            'schoolYear',
            'classes',
            'teachers',
            'teacherSubjects.teacher',
            'teacherSubjects.classRoom',
            'teacherSubjects.schoolYear',
        ]);

        return view(
            'subjects.show',
            compact('subject')
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Subject $subject)
    {
        $this->ensureAdmin();

        $this->authorizeSubject($subject);

        $establishment = $this->establishment();

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        return view(
            'subjects.edit',
            compact(
                'subject',
                'establishment',
                'schoolYears'
            )
        );
    }

    /**
     * Mise à jour d'une matière.
     */
    public function update(
        Request $request,
        Subject $subject
    ) {
        $this->ensureAdmin();

        $this->authorizeSubject($subject);

        $establishment = $this->establishment();

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                Rule::exists('school_years', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'establishment_id',
                                $establishment->id
                            )
                    ),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $nom = trim($validated['nom']);

        /*
         * Empêche deux matières ayant le même nom
         * dans le même établissement et la même année.
         */
        $duplicate = Subject::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->where(
                'id',
                '!=',
                $subject->id
            )
            ->whereRaw(
                'LOWER(nom) = ?',
                [mb_strtolower($nom)]
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'nom' =>
                        'Cette matière existe déjà pour cette année scolaire.',
                ]);
        }

        /*
         * Si l'année scolaire change, les anciennes affectations
         * de cette matière peuvent devenir incohérentes.
         *
         * On bloque donc le changement lorsqu'il existe
         * déjà des affectations enseignant/classe.
         */
        if (
            (int) $subject->school_year_id !==
            (int) $validated['school_year_id']
        ) {
            $hasTeacherAssignments = $subject
                ->teacherSubjects()
                ->exists();

            if ($hasTeacherAssignments) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'school_year_id' =>
                            'Impossible de changer l’année scolaire : cette matière possède déjà des affectations enseignants/classes.',
                    ]);
            }
        }

        $subject->update([
            'school_year_id' => $validated['school_year_id'],
            'school_type_id' => $establishment->school_type_id,
            'code' => isset($validated['code'])
                ? trim($validated['code'])
                : null,
            'nom' => $nom,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Matière modifiée avec succès.'
            );
    }

    /**
     * Suppression d'une matière.
     */
    public function destroy(Subject $subject)
    {
        $this->ensureAdmin();

        $this->authorizeSubject($subject);

        /*
         * Vérifie si la matière possède encore
         * des affectations enseignants.
         */
        if ($subject->teacherSubjects()->exists()) {
            return redirect()
                ->route('subjects.index')
                ->withErrors([
                    'subject' =>
                        'Impossible de supprimer cette matière car elle possède encore des affectations enseignants.',
                ]);
        }

        /*
         * Vérifie également les affectations
         * matière/classe existantes.
         */
        if ($subject->classRoomSubjects()->exists()) {
            return redirect()
                ->route('subjects.index')
                ->withErrors([
                    'subject' =>
                        'Impossible de supprimer cette matière car elle est encore affectée à une ou plusieurs classes.',
                ]);
        }

        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Matière supprimée avec succès.'
            );
    }
}
