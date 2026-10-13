<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\SchoolYear;
use App\Models\EmploiDuTemps;
use App\Models\Salle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalleController extends Controller
{
    /**
     * Liste des salles de l'établissement courant.
     */
    public function index(Request $request): View
    {
        $establishmentId = session('establishment_id');

        abort_unless($establishmentId, 403);

        $search = trim((string) $request->input('search'));

        $salles = Salle::query()
            ->where('establishment_id', $establishmentId)
            ->withCount('classRooms')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('salles.index', compact(
            'salles',
            'search'
        ));
    }

    /**
     * Formulaire création.
     */
    public function create(): View
    {
        abort_unless(session('establishment_id'), 403);

        return view('salles.create');
    }

    /**
     * Enregistrer une salle.
     */
    public function store(Request $request): RedirectResponse
    {
        $establishmentId = session('establishment_id');

        abort_unless($establishmentId, 403);

        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:150',
            ],
            'capacite' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'nom.required' => 'Le nom de la salle est obligatoire.',
            'nom.max' => 'Le nom de la salle ne doit pas dépasser 150 caractères.',
            'capacite.integer' => 'La capacité doit être un nombre entier.',
            'capacite.min' => 'La capacité doit être supérieure à 0.',
            'description.max' => 'La description ne doit pas dépasser 2000 caractères.',
        ]);

        $exists = Salle::query()
            ->where('establishment_id', $establishmentId)
            ->whereRaw('LOWER(nom) = ?', [mb_strtolower(trim($validated['nom']))])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'nom' => 'Cette salle existe déjà dans cet établissement.',
                ]);
        }

        Salle::create([
            'establishment_id' => $establishmentId,
            'nom' => trim($validated['nom']),
            'capacite' => $validated['capacite'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('salles.index')
            ->with('success', 'La salle a été ajoutée avec succès.');
    }

    /**
     * Affichage d'une salle avec classes, étudiants et enseignants.
     */
    public function show(Salle $salle): View
    {
        $this->authorizeEstablishment($salle);

        $establishmentId = (int) $salle->establishment_id;

        /*
         * L'année scolaire doit appartenir à l'établissement courant.
         * Si la session n'est plus valide, on choisit l'année active,
         * puis la plus récente si aucune n'est active.
         */
        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishmentId)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $schoolYearId = (int) session('school_year_id');

        $schoolYear = $schoolYears->firstWhere('id', $schoolYearId);

        if (!$schoolYear) {
            $schoolYear = $schoolYears->first();

            if ($schoolYear) {
                session([
                    'school_year_id' => $schoolYear->id,
                    'school_year_name' => $schoolYear->name,
                ]);
            } else {
                session()->forget([
                    'school_year_id',
                    'school_year_name',
                ]);
            }
        }

        $schoolYearId = $schoolYear ? (int) $schoolYear->id : null;

        /*
         * Classes de la salle :
         * - affectation principale par salle_id ;
         * - compatibilité avec les anciennes données textuelles ;
         * - séparation par année scolaire ;
         * - les anciennes classes sans année ne sont retenues que
         *   si elles possèdent une inscription de l'année sélectionnée.
         */
        $classRooms = ClassRoom::query()
            ->where('establishment_id', $establishmentId)
            ->where(function ($query) use ($salle) {
                $query->where('salle_id', $salle->id)
                    ->orWhere(function ($legacy) use ($salle) {
                        $legacy->whereNull('salle_id')
                            ->where('salle', $salle->nom);
                    });
            })
            ->when($schoolYearId !== null, function ($query) use (
                $schoolYearId,
                $establishmentId
            ) {
                $query->where(function ($yearQuery) use (
                    $schoolYearId,
                    $establishmentId
                ) {
                    $yearQuery->where('school_year_id', $schoolYearId)
                        ->orWhere(function ($legacy) use (
                            $schoolYearId,
                            $establishmentId
                        ) {
                            $legacy->whereNull('school_year_id')
                                ->whereHas('inscriptions', function ($inscriptions) use (
                                    $schoolYearId,
                                    $establishmentId
                                ) {
                                    $inscriptions
                                        ->where('school_year_id', $schoolYearId)
                                        ->where('establishment_id', $establishmentId);
                                });
                        });
                });
            })
            ->with([
                'teacher',
                'schoolYear',
                'inscriptions' => function ($query) use (
                    $schoolYearId,
                    $establishmentId
                ) {
                    $query->where('establishment_id', $establishmentId)
                        ->when(
                            $schoolYearId !== null,
                            fn ($q) => $q->where(
                                'school_year_id',
                                $schoolYearId
                            )
                        )
                        ->with(['student', 'schoolYear'])
                        ->orderBy('id');
                },
            ])
            ->orderBy('nom_classe')
            ->get();

        /*
         * Planning réel de cette salle, limité à l'établissement
         * et à l'année scolaire sélectionnée.
         */
        $emplois = EmploiDuTemps::query()
            ->where('establishment_id', $establishmentId)
            ->where('salle_id', $salle->id)
            ->when(
                $schoolYearId !== null,
                fn ($query) => $query->where(
                    'school_year_id',
                    $schoolYearId
                )
            )
            ->with([
                'classRoom',
                'subject',
                'teacher',
                'schoolYear',
            ])
            ->orderByRaw(
                "FIELD(jour, 'Lundi', 'Mardi', 'Mercredi',
                 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')"
            )
            ->orderBy('heure_debut')
            ->get();

        /*
         * Liste des élèves inscrits dans les classes de la salle.
         * Correction : class_room contient bien la classe, et non
         * l'objet inscription.
         */
        $students = $classRooms
            ->flatMap(function ($classRoom) {
                return $classRoom->inscriptions
                    ->filter(fn ($inscription) => $inscription->student !== null)
                    ->map(fn ($inscription) => [
                        'student' => $inscription->student,
                        'class_room' => $classRoom,
                        'inscription' => $inscription,
                    ]);
            })
            ->unique(fn ($item) => $item['student']->id)
            ->values();

        /*
         * Enseignants :
         * - enseignant principal de la classe ;
         * - enseignants présents dans le planning de cette salle.
         */
        $teacherItems = collect();

        foreach ($classRooms as $classRoom) {
            if ($classRoom->teacher) {
                $key = $classRoom->teacher->id . '-' . $classRoom->id . '-principal';

                $teacherItems->put($key, [
                    'teacher' => $classRoom->teacher,
                    'class_room' => $classRoom,
                    'subject' => null,
                    'jour' => null,
                    'heure_debut' => null,
                    'heure_fin' => null,
                ]);
            }
        }

        foreach ($emplois as $emploi) {
            if (!$emploi->teacher) {
                continue;
            }

            $key = implode('-', [
                $emploi->teacher->id,
                $emploi->class_room_id,
                $emploi->subject_id,
            ]);

            $teacherItems->put($key, [
                'teacher' => $emploi->teacher,
                'class_room' => $emploi->classRoom,
                'subject' => $emploi->subject,
                'jour' => $emploi->jour,
                'heure_debut' => $emploi->heure_debut,
                'heure_fin' => $emploi->heure_fin,
            ]);
        }

        $teachers = $teacherItems->values();

        // Une matière peut être programmée dans plusieurs classes.
        // Conserver une entrée distincte pour chaque couple matière/classe.
        $subjects = $emplois
            ->filter(fn ($emploi) => $emploi->subject !== null)
            ->unique(fn ($emploi) => $emploi->subject->id . '-' . $emploi->class_room_id)
            ->values();

        $totalStudents = $students->count();
        $totalClasses = $classRooms->count();
        $totalTeachers = $teachers
            ->pluck('teacher.id')
            ->filter()
            ->unique()
            ->count();

        return view('salles.show', compact(
            'salle',
            'schoolYear',
            'schoolYears',
            'classRooms',
            'students',
            'teachers',
            'subjects',
            'emplois',
            'totalStudents',
            'totalClasses',
            'totalTeachers'
        ));
    }

    /**
     * Formulaire modification.
     */
    public function edit(Salle $salle): View
    {
        $this->authorizeEstablishment($salle);

        return view('salles.edit', compact('salle'));
    }

    /**
     * Modifier une salle.
     */
    public function update(Request $request, Salle $salle): RedirectResponse
    {
        $this->authorizeEstablishment($salle);

        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:150',
            ],
            'capacite' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'nom.required' => 'Le nom de la salle est obligatoire.',
            'capacite.integer' => 'La capacité doit être un nombre entier.',
            'capacite.min' => 'La capacité doit être supérieure à 0.',
        ]);

        $exists = Salle::query()
            ->where('establishment_id', $salle->establishment_id)
            ->where('id', '!=', $salle->id)
            ->whereRaw('LOWER(nom) = ?', [mb_strtolower(trim($validated['nom']))])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'nom' => 'Une autre salle porte déjà ce nom.',
                ]);
        }

        $oldName = $salle->nom;
        $newName = trim($validated['nom']);

        DB::transaction(function () use (
            $salle,
            $validated,
            $oldName,
            $newName
        ) {
            $salle->update([
                'nom' => $newName,
                'capacite' => $validated['capacite'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            /*
             * Mise à jour des anciennes classes qui utilisent encore
             * la colonne texte "salle".
             *
             * On ne touche pas aux classes déjà liées avec salle_id.
             */
            if ($oldName !== $newName) {
                ClassRoom::query()
                    ->where('establishment_id', $salle->establishment_id)
                    ->whereNull('salle_id')
                    ->where('salle', $oldName)
                    ->update([
                        'salle' => $newName,
                    ]);
            }
        });

        return redirect()
            ->route('salles.index')
            ->with('success', 'La salle a été modifiée avec succès.');
    }

    /**
     * Supprimer une salle.
     */
    public function destroy(Salle $salle): RedirectResponse
    {
        $this->authorizeEstablishment($salle);

        $linkedClasses = ClassRoom::query()
            ->where('establishment_id', $salle->establishment_id)
            ->where('salle_id', $salle->id)
            ->count();

        if ($linkedClasses > 0) {
            return redirect()
                ->route('salles.index')
                ->with('error', 'Impossible de supprimer cette salle car des classes y sont encore affectées.');
        }

        $salle->delete();

        return redirect()
            ->route('salles.index')
            ->with('success', 'La salle a été supprimée avec succès.');
    }

    /**
     * Vérifie que la salle appartient à l'établissement courant.
     */
    private function authorizeEstablishment(Salle $salle): void
    {
        abort_unless(
            session('establishment_id') &&
            (int) $salle->establishment_id === (int) session('establishment_id'),
            403
        );
    }
}
