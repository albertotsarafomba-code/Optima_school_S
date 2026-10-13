<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Establishment;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\ParentModel;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultatController extends Controller
{
    /**
     * Établissement actuellement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $establishments = $user->establishments()
                ->with('schoolType')
                ->orderBy('id')
                ->get();
        } else {
            $establishments = $user->assignedEstablishments()
                ->with('schoolType')
                ->orderBy('id')
                ->get();
        }

        if ($establishments->isEmpty()) {
            abort(403, 'Aucun établissement accessible.');
        }

        $establishmentId = session('establishment_id');

        if ($establishmentId) {
            $establishment = $establishments->firstWhere('id', (int) $establishmentId);

            if ($establishment) {
                return $establishment;
            }

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);
        }

        if ($establishments->count() === 1) {
            $establishment = $establishments->first();

            session([
                'establishment_id' => $establishment->id,
                'establishment_name' => $establishment->nom,
            ]);

            return $establishment;
        }

        abort(redirect()->route('establishments.choose'));
    }

    /**
     * Liste des résultats.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Années scolaires uniquement pour l'établissement courant.
         */
        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('start_date')
            ->get();

        if ($schoolYears->isEmpty()) {
            return view('resultats.index', [
                'establishment' => $establishment,
                'schoolYears' => $schoolYears,
                'classes' => collect(),
                'selectedSchoolYear' => null,
                'selectedClass' => null,
                'studentsResults' => collect(),
            ]);
        }

        /*
         * Année sélectionnée :
         * priorité au formulaire, sinon session.
         */
        $schoolYearId = $request->input(
            'school_year_id',
            session('school_year_id')
        );

        $selectedSchoolYear = $schoolYears->firstWhere(
            'id',
            (int) $schoolYearId
        );

        /*
         * Si aucune année valide n'est sélectionnée,
         * on utilise l'année active/session ou la première disponible.
         */
        if (!$selectedSchoolYear) {
            $selectedSchoolYear = $schoolYears->firstWhere('is_active', true)
                ?? $schoolYears->first();

            $schoolYearId = $selectedSchoolYear->id;
        }

        session([
            'school_year_id' => $selectedSchoolYear->id,
            'school_year_name' => $selectedSchoolYear->name,
        ]);

        /*
         * Étudiants autorisés :
         *
         * - Les autres rôles conservent les règles actuelles.
         * - Un parent ne peut consulter que ses propres enfants,
         *   liés par parent_student, inscrits dans l'établissement
         *   et l'année scolaire sélectionnés.
         */
        $allowedStudentIds = null;

        if ($request->user()->role === 'parent') {
            $parentProfiles = ParentModel::query()
                ->where('user_id', $request->user()->id)
                ->where('establishment_id', $establishment->id)
                ->with('students:id')
                ->get();

            if ($parentProfiles->isEmpty()) {
                abort(
                    403,
                    'Compte parent introuvable pour cet établissement.'
                );
            }

            $linkedStudentIds = $parentProfiles
                ->flatMap(function ($parent) {
                    return $parent->students->pluck('id');
                })
                ->unique()
                ->values();

            if ($linkedStudentIds->isEmpty()) {
                abort(
                    403,
                    'Aucun enfant n’est associé à votre compte parent.'
                );
            }

            $allowedStudentIds = Inscription::query()
                ->where('establishment_id', $establishment->id)
                ->where('school_year_id', $selectedSchoolYear->id)
                ->whereIn('student_id', $linkedStudentIds)
                ->pluck('student_id')
                ->filter()
                ->unique()
                ->values();
        }

        /*
         * Classes de l'établissement ET de l'année scolaire.
         *
         * Pour un parent, seules les classes dans lesquelles
         * au moins un de ses élèves est inscrit sont affichées.
         */
        $classesQuery = ClassRoom::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $selectedSchoolYear->id);

        if ($allowedStudentIds !== null) {
            $classesQuery->whereHas('inscriptions', function ($query) use ($allowedStudentIds, $selectedSchoolYear, $establishment) {
                $query->where('establishment_id', $establishment->id)
                    ->where('school_year_id', $selectedSchoolYear->id)
                    ->whereIn('student_id', $allowedStudentIds);
            });
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        $selectedClass = null;
        $studentsResults = collect();

        $classId = $request->input('class_room_id');

        if ($classId) {
            $selectedClass = $classes->firstWhere('id', (int) $classId);

            if (!$selectedClass) {
                abort(404, 'Classe introuvable pour cette année scolaire.');
            }

            /*
             * Le parent ne peut consulter une classe
             * que si l'un de ses élèves y est inscrit
             * pour l'année scolaire sélectionnée.
             */
            if ($allowedStudentIds !== null) {
                $hasChildInSelectedClass = Inscription::query()
                    ->where('establishment_id', $establishment->id)
                    ->where('school_year_id', $selectedSchoolYear->id)
                    ->where('class_room_id', $selectedClass->id)
                    ->whereIn('student_id', $allowedStudentIds)
                    ->exists();

                if (!$hasChildInSelectedClass) {
                    abort(403, 'Vous n’avez pas accès aux résultats de cette classe.');
                }
            }

            /*
             * Étudiants inscrits dans CET établissement,
             * CETTE classe et CETTE année scolaire.
             */
            $inscriptions = Inscription::query()
                ->with('student')
                ->where('establishment_id', $establishment->id)
                ->where('school_year_id', $selectedSchoolYear->id)
                ->where('class_room_id', $selectedClass->id)
                ->when(
                    $allowedStudentIds !== null,
                    fn ($query) => $query->whereIn('student_id', $allowedStudentIds)
                )
                ->get();

            $studentIds = $inscriptions
                ->pluck('student_id')
                ->filter()
                ->unique()
                ->values();

            /*
             * Matières associées à la classe.
             */
            $subjects = $selectedClass->subjects()
                ->get()
                ->unique('id')
                ->values();

            /*
             * Notes uniquement de l'établissement,
             * de l'année et de la classe sélectionnés.
             */
            $notes = Note::query()
                ->with(['student', 'subject'])
                ->where('establishment_id', $establishment->id)
                ->where('school_year_id', $selectedSchoolYear->id)
                ->where('class_room_id', $selectedClass->id)
                ->whereIn('student_id', $studentIds)
                ->get();

            foreach ($inscriptions as $inscription) {
                $student = $inscription->student;

                if (!$student) {
                    continue;
                }

                $studentNotes = $notes
                    ->where('student_id', $student->id);

                $subjectResults = collect();
                $totalWeightedSubjects = 0;
                $totalSubjectCoefficients = 0;

                foreach ($subjects as $subject) {
                    $subjectNotes = $studentNotes
                        ->where('subject_id', $subject->id);

                    /*
                     * Une matière sans note n'entre pas
                     * dans le calcul de la moyenne générale.
                     */
                    if ($subjectNotes->isEmpty()) {
                        $subjectResults->push([
                            'subject' => $subject,
                            'average' => null,
                            'coefficient' => (float) ($subject->pivot->coefficient ?? 1),
                            'notes_count' => 0,
                        ]);

                        continue;
                    }

                    /*
                     * Moyenne pondérée des évaluations :
                     *
                     * Σ(note × coefficient évaluation)
                     * --------------------------------
                     * Σ(coefficient évaluation)
                     */
                    $sumWeightedNotes = 0;
                    $sumEvaluationCoefficients = 0;

                    foreach ($subjectNotes as $note) {
                        $noteCoefficient = (float) ($note->coefficient ?? 1);
                        $noteValue = (float) $note->note;

                        $sumWeightedNotes += $noteValue * $noteCoefficient;
                        $sumEvaluationCoefficients += $noteCoefficient;
                    }

                    $subjectAverage = $sumEvaluationCoefficients > 0
                        ? $sumWeightedNotes / $sumEvaluationCoefficients
                        : null;

                    /*
                     * Coefficient de la matière.
                     * Il vient du pivot class_room_subjects.
                     * Si aucun coefficient n'est défini, 1 par défaut.
                     */
                    $subjectCoefficient = (float) (
                        $subject->pivot->coefficient ?? 1
                    );

                    if ($subjectAverage !== null) {
                        $totalWeightedSubjects +=
                            $subjectAverage * $subjectCoefficient;

                        $totalSubjectCoefficients += $subjectCoefficient;
                    }

                    $subjectResults->push([
                        'subject' => $subject,
                        'average' => $subjectAverage !== null
                            ? round($subjectAverage, 2)
                            : null,
                        'coefficient' => $subjectCoefficient,
                        'notes_count' => $subjectNotes->count(),
                    ]);
                }

                /*
                 * Moyenne générale :
                 *
                 * Σ(moyenne matière × coefficient matière)
                 * -----------------------------------------
                 * Σ(coefficient matière)
                 */
                $generalAverage = $totalSubjectCoefficients > 0
                    ? $totalWeightedSubjects / $totalSubjectCoefficients
                    : null;

                $studentsResults->push([
                    'student' => $student,
                    'subject_results' => $subjectResults,
                    'general_average' => $generalAverage !== null
                        ? round($generalAverage, 2)
                        : null,
                ]);
            }

            /*
             * Classement :
             * uniquement les étudiants ayant une moyenne générale.
             */
            $ranked = $studentsResults
                ->filter(fn ($result) => $result['general_average'] !== null)
                ->sortByDesc('general_average')
                ->values();

            $rank = 0;
            $previousAverage = null;

            foreach ($ranked as $index => $result) {
                if ($previousAverage === null ||
                    $result['general_average'] < $previousAverage) {
                    $rank = $index + 1;
                }

                foreach ($studentsResults as $key => $studentResult) {
                    if (
                        $studentResult['student']->id ===
                        $result['student']->id
                    ) {
                        $studentsResults[$key]['rank'] = $rank;
                        break;
                    }
                }

                $previousAverage = $result['general_average'];
            }

            /*
             * Les étudiants sans note complète n'ont pas de rang.
             */
            $studentsResults = $studentsResults->map(function ($result) {
                if (!array_key_exists('rank', $result)) {
                    $result['rank'] = null;
                }

                return $result;
            });

            /*
             * Affichage par rang.
             */
            $studentsResults = $studentsResults
                ->sortBy(function ($result) {
                    return $result['rank'] ?? PHP_INT_MAX;
                })
                ->values();
        }

        return view('resultats.index', [
            'establishment' => $establishment,
            'schoolYears' => $schoolYears,
            'classes' => $classes,
            'selectedSchoolYear' => $selectedSchoolYear,
            'selectedClass' => $selectedClass,
            'studentsResults' => $studentsResults,
        ]);
    }
}
