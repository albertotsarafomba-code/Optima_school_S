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

class BulletinController extends Controller
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
            $establishment = $establishments->firstWhere(
                'id',
                (int) $establishmentId
            );

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
     * Liste de sélection des bulletins.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('start_date')
            ->get();

        $selectedSchoolYear = null;
        $classes = collect();
        $students = collect();

        if ($schoolYears->isNotEmpty()) {
            $schoolYearId = $request->input(
                'school_year_id',
                session('school_year_id')
            );

            $selectedSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) $schoolYearId
            );

            if (!$selectedSchoolYear) {
                $selectedSchoolYear =
                    $schoolYears->firstWhere('is_active', true)
                    ?? $schoolYears->first();
            }

            session([
                'school_year_id' => $selectedSchoolYear->id,
                'school_year_name' => $selectedSchoolYear->name,
            ]);

            /*
             * Classes accessibles.
             *
             * Pour un parent, uniquement les classes dans lesquelles
             * au moins un élève est inscrit dans l'établissement
             * et l'année scolaire sélectionnée.
             */
            $classesQuery = ClassRoom::query()
                ->where('establishment_id', $establishment->id)
                ->where('school_year_id', $selectedSchoolYear->id);

            if ($request->user()->role === 'parent') {
                $classesQuery->whereHas('inscriptions', function ($query) use (
                    $establishment,
                    $selectedSchoolYear
                ) {
                    $query
                        ->where('establishment_id', $establishment->id)
                        ->where('school_year_id', $selectedSchoolYear->id);
                });
            }

            $classes = $classesQuery
                ->orderBy('nom_classe')
                ->get();

            $classId = $request->input('class_room_id');

            if ($classId) {
                $selectedClass = $classes->firstWhere(
                    'id',
                    (int) $classId
                );

                if (!$selectedClass) {
                    abort(
                        404,
                        'Classe introuvable pour cette année scolaire.'
                    );
                }

                $studentsQuery = Inscription::query()
                    ->with('student')
                    ->where('establishment_id', $establishment->id)
                    ->where('school_year_id', $selectedSchoolYear->id)
                    ->where('class_room_id', $selectedClass->id);

                /*
                 * Sécurité Parent :
                 * uniquement les enfants réellement liés au compte
                 * via la table parent_student.
                 */
                if ($request->user()->role === 'parent') {
                    $allowedStudentIds = $this->parentAllowedStudentIds(
                        $request,
                        $establishment
                    );

                    $studentsQuery->whereIn(
                        'student_id',
                        $allowedStudentIds
                    );
                }

                $students = $studentsQuery
                    ->get()
                    ->pluck('student')
                    ->filter()
                    ->unique('id')
                    ->sortBy(function ($student) {
                        return strtoupper(
                            trim(($student->nom ?? '') . ' ' . ($student->prenom ?? ''))
                        );
                    })
                    ->values();
            }
        }

        return view('bulletins.index', [
            'establishment' => $establishment,
            'schoolYears' => $schoolYears,
            'selectedSchoolYear' => $selectedSchoolYear,
            'classes' => $classes,
            'students' => $students,
            'selectedClassId' => $request->input('class_room_id'),
            'selectedStudentId' => $request->input('student_id'),
        ]);
    }

    /**
     * IDs des élèves réellement liés au compte parent.
     *
     * Un parent ne peut consulter que ses propres enfants.
     */
    private function parentAllowedStudentIds(
        Request $request,
        Establishment $establishment
    ) {
        if ($request->user()->role !== 'parent') {
            return null;
        }

        $parentProfiles = ParentModel::query()
            ->with('students')
            ->where('user_id', $request->user()->id)
            ->where('establishment_id', $establishment->id)
            ->get();

        if ($parentProfiles->isEmpty()) {
            abort(
                403,
                'Compte parent introuvable pour cet établissement.'
            );
        }

        $studentIds = $parentProfiles
            ->flatMap(function ($parent) {
                return $parent->students->pluck('id');
            })
            ->unique()
            ->values();

        if ($studentIds->isEmpty()) {
            abort(
                403,
                'Aucun enfant n’est associé à votre compte parent.'
            );
        }

        return $studentIds;
    }

    /**
     * Afficher le bulletin individuel d'un étudiant.
     */
    public function show(
        Request $request,
        string $student
    ) {
        $establishment = $this->currentEstablishment();

        $schoolYearId = $request->input(
            'school_year_id',
            session('school_year_id')
        );

        $schoolYear = SchoolYear::query()
            ->where('id', $schoolYearId)
            ->where('establishment_id', $establishment->id)
            ->firstOrFail();

        /*
         * L'étudiant doit obligatoirement être inscrit
         * dans l'établissement, la classe et l'année sélectionnés.
         */
        $inscription = Inscription::query()
            ->with([
                'student',
                'classRoom',
            ])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('student_id', $student);

        /*
         * Sécurité Parent :
         * le bulletin doit obligatoirement appartenir à l'un
         * des enfants liés au compte via parent_student.
         */
        if ($request->user()->role === 'parent') {
            $allowedStudentIds = $this->parentAllowedStudentIds(
                $request,
                $establishment
            );

            if (!$allowedStudentIds->contains((int) $student)) {
                abort(
                    403,
                    'Vous ne pouvez consulter que les bulletins de vos enfants.'
                );
            }

            $inscription->whereIn(
                'student_id',
                $allowedStudentIds
            );
        }

        $inscription = $inscription
            ->when(
                $request->input('class_room_id'),
                fn ($query, $classId) =>
                    $query->where('class_room_id', $classId)
            )
            ->first();

        if (!$inscription || !$inscription->student || !$inscription->classRoom) {
            abort(
                404,
                'Étudiant introuvable dans cette année scolaire et cet établissement.'
            );
        }

        $classRoom = $inscription->classRoom;
        $studentModel = $inscription->student;

        /*
         * Matières de la classe.
         */
        $subjects = $classRoom->subjects()
            ->get()
            ->unique('id')
            ->values();

        /*
         * Notes uniquement :
         * - établissement courant
         * - année scolaire sélectionnée
         * - classe de l'inscription
         * - étudiant sélectionné
         */
        $notes = Note::query()
            ->with('subject')
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('class_room_id', $classRoom->id)
            ->where('student_id', $studentModel->id)
            ->get();

        /*
         * Calcul des résultats par matière.
         */
        $subjectResults = collect();

        $totalWeightedSubjects = 0;
        $totalSubjectCoefficients = 0;

        foreach ($subjects as $subject) {
            $subjectNotes = $notes->where(
                'subject_id',
                $subject->id
            );

            $subjectCoefficient = (float) (
                $subject->pivot->coefficient ?? 1
            );

            if ($subjectNotes->isEmpty()) {
                $subjectResults->push([
                    'subject' => $subject,
                    'average' => null,
                    'coefficient' => $subjectCoefficient,
                    'notes_count' => 0,
                ]);

                continue;
            }

            $sumWeightedNotes = 0;
            $sumEvaluationCoefficients = 0;

            foreach ($subjectNotes as $note) {
                $noteCoefficient = (float) (
                    $note->coefficient ?? 1
                );

                $noteValue = (float) $note->note;

                $sumWeightedNotes +=
                    $noteValue * $noteCoefficient;

                $sumEvaluationCoefficients +=
                    $noteCoefficient;
            }

            $subjectAverage = $sumEvaluationCoefficients > 0
                ? $sumWeightedNotes / $sumEvaluationCoefficients
                : null;

            if ($subjectAverage !== null) {
                $totalWeightedSubjects +=
                    $subjectAverage * $subjectCoefficient;

                $totalSubjectCoefficients +=
                    $subjectCoefficient;
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
         * Moyenne générale de l'étudiant.
         */
        $generalAverage = $totalSubjectCoefficients > 0
            ? $totalWeightedSubjects /
              $totalSubjectCoefficients
            : null;

        $generalAverage = $generalAverage !== null
            ? round($generalAverage, 2)
            : null;

        /*
         * Calcul du classement de la classe.
         *
         * On recalcule les moyennes des étudiants inscrits
         * dans la même classe, la même année et le même établissement.
         */
        $classInscriptions = Inscription::query()
            ->with('student')
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('class_room_id', $classRoom->id)
            ->get();

        $classStudentIds = $classInscriptions
            ->pluck('student_id')
            ->filter()
            ->unique()
            ->values();

        $classNotes = Note::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('class_room_id', $classRoom->id)
            ->whereIn('student_id', $classStudentIds)
            ->get();

        $classAverages = collect();

        foreach ($classStudentIds as $studentId) {
            $studentNotes = $classNotes->where(
                'student_id',
                $studentId
            );

            $weightedSubjects = 0;
            $subjectCoefficients = 0;

            foreach ($subjects as $subject) {
                $subjectNotes = $studentNotes->where(
                    'subject_id',
                    $subject->id
                );

                if ($subjectNotes->isEmpty()) {
                    continue;
                }

                $weightedNotes = 0;
                $evaluationCoefficients = 0;

                foreach ($subjectNotes as $note) {
                    $evaluationCoefficient = (float) (
                        $note->coefficient ?? 1
                    );

                    $weightedNotes +=
                        (float) $note->note *
                        $evaluationCoefficient;

                    $evaluationCoefficients +=
                        $evaluationCoefficient;
                }

                if ($evaluationCoefficients <= 0) {
                    continue;
                }

                $subjectAverage =
                    $weightedNotes /
                    $evaluationCoefficients;

                $subjectCoefficient = (float) (
                    $subject->pivot->coefficient ?? 1
                );

                $weightedSubjects +=
                    $subjectAverage *
                    $subjectCoefficient;

                $subjectCoefficients +=
                    $subjectCoefficient;
            }

            if ($subjectCoefficients > 0) {
                $classAverages->push([
                    'student_id' => $studentId,
                    'average' => round(
                        $weightedSubjects /
                        $subjectCoefficients,
                        2
                    ),
                ]);
            }
        }

        $ranked = $classAverages
            ->sortByDesc('average')
            ->values();

        $rank = null;
        $previousAverage = null;
        $currentRank = 0;

        foreach ($ranked as $index => $item) {
            if (
                $previousAverage === null ||
                $item['average'] < $previousAverage
            ) {
                $currentRank = $index + 1;
            }

            if ((int) $item['student_id'] === (int) $studentModel->id) {
                $rank = $currentRank;
                break;
            }

            $previousAverage = $item['average'];
        }

        /*
         * Mention.
         */
        $mention = null;

        if ($generalAverage !== null) {
            $mention = match (true) {
                $generalAverage >= 16 => 'Très bien',
                $generalAverage >= 14 => 'Bien',
                $generalAverage >= 12 => 'Assez bien',
                $generalAverage >= 10 => 'Passable',
                default => 'Insuffisant',
            };
        }

        return view('bulletins.show', [
            'establishment' => $establishment,
            'schoolYear' => $schoolYear,
            'classRoom' => $classRoom,
            'student' => $studentModel,
            'inscription' => $inscription,
            'subjectResults' => $subjectResults,
            'generalAverage' => $generalAverage,
            'rank' => $rank,
            'classSize' => $classStudentIds->count(),
            'mention' => $mention,
        ]);
    }
}
