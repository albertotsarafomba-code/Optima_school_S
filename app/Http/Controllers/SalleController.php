<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
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

        $salle->load([
            'classRooms.teacher',
            'classRooms.inscriptions.student',
            'classRooms.inscriptions.schoolYear',
        ]);

        /*
         * On prend les classes liées par salle_id.
         *
         * Pour conserver les anciennes données, on ajoute également
         * les anciennes classes dont class_rooms.salle correspond
         * au nom de la salle.
         */
        $classRooms = ClassRoom::query()
            ->where('establishment_id', $salle->establishment_id)
            ->with([
                'teacher',
                'inscriptions.student',
                'inscriptions.schoolYear',
            ])
            ->where(function ($query) use ($salle) {
                $query
                    ->where('salle_id', $salle->id)
                    ->orWhere(function ($q) use ($salle) {
                        $q->whereNull('salle_id')
                            ->where('salle', $salle->nom);
                    });
            })
            ->orderBy('nom_classe')
            ->get();

        $students = $classRooms
            ->flatMap(function ($classRoom) {
                return $classRoom->inscriptions
                    ->filter(function ($inscription) {
                        return $inscription->student !== null;
                    })
                    ->map(function ($inscription) {
                        return [
                            'student' => $inscription->student,
                            'class_room' => $inscription,
                            'inscription' => $inscription,
                        ];
                    });
            })
            ->unique(function ($item) {
                return $item['student']->id;
            })
            ->values();

        $teachers = $classRooms
            ->filter(function ($classRoom) {
                return $classRoom->teacher !== null;
            })
            ->map(function ($classRoom) {
                return [
                    'teacher' => $classRoom->teacher,
                    'class_room' => $classRoom,
                ];
            })
            ->unique(function ($item) {
                return $item['teacher']->id;
            })
            ->values();

        return view('salles.show', compact(
            'salle',
            'classRooms',
            'students',
            'teachers'
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
