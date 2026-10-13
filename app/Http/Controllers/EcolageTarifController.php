<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\EcolageTarif;
use App\Models\Establishment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EcolageTarifController extends Controller
{
    /**
     * Vérifie et retourne l'établissement actuellement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(403, 'Aucun établissement n’est sélectionné.');
        }

        $user = auth()->user();

        if ($user->role === 'admin') {
            $establishment = Establishment::find($establishmentId);
        } else {
            $establishment = $user
                ->assignedEstablishments()
                ->where('establishments.id', $establishmentId)
                ->first();
        }

        if (!$establishment) {
            abort(403, 'Vous n’avez pas accès à cet établissement.');
        }

        return $establishment;
    }

    /**
     * Retourne une année scolaire appartenant à l'établissement courant.
     */
    private function currentSchoolYear(
        Establishment $establishment,
        ?int $schoolYearId = null
    ): SchoolYear {
        $id = $schoolYearId ?: session('school_year_id');

        if (!$id) {
            $id = SchoolYear::where(
                'establishment_id',
                $establishment->id
            )
                ->where('is_active', true)
                ->orderByDesc('id')
                ->value('id');
        }

        if (!$id) {
            abort(404, 'Aucune année scolaire disponible.');
        }

        $schoolYear = SchoolYear::where('id', $id)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$schoolYear) {
            abort(
                403,
                'Cette année scolaire n’appartient pas à cet établissement.'
            );
        }

        return $schoolYear;
    }

    /**
     * Liste des années scolaires de l'établissement.
     */
    private function schoolYears(Establishment $establishment)
    {
        return SchoolYear::where(
            'establishment_id',
            $establishment->id
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Liste des tarifs.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $request->filled('school_year_id')
                ? (int) $request->school_year_id
                : null
        );

        $schoolYears = $this->schoolYears($establishment);

        $classes = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('nom_classe')
            ->get();

        $tarifs = EcolageTarif::with('classRoom')
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('class_room_id')
            ->get();

        return view('paiements.tarifs.index', [
            'establishment' => $establishment,
            'schoolYear' => $schoolYear,
            'schoolYears' => $schoolYears,
            'classes' => $classes,
            'tarifs' => $tarifs,
        ]);
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $request->filled('school_year_id')
                ? (int) $request->school_year_id
                : null
        );

        $classes = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('nom_classe')
            ->get();

        return view('paiements.tarifs.create', [
            'establishment' => $establishment,
            'schoolYear' => $schoolYear,
            'schoolYears' => $this->schoolYears($establishment),
            'classes' => $classes,
        ]);
    }

    /**
     * Enregistre un nouveau tarif.
     */
    public function store(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $request->filled('school_year_id')
                ? (int) $request->school_year_id
                : null
        );

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
                Rule::exists('class_rooms', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'establishment_id',
                                $establishment->id
                            )
                            ->where(
                                'school_year_id',
                                $schoolYear->id
                            )
                    ),
            ],

            'montant_mensuel' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],
        ]);

        $exists = EcolageTarif::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->where(
                'class_room_id',
                $validated['class_room_id']
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'Un tarif existe déjà pour cette classe et cette année scolaire.',
                ]);
        }

        EcolageTarif::create([
            'establishment_id' => $establishment->id,
            'school_year_id' => $schoolYear->id,
            'class_room_id' => $validated['class_room_id'],
            'montant_mensuel' => $validated['montant_mensuel'],
        ]);

        return redirect()
            ->route('paiements.tarifs.index', [
                'school_year_id' => $schoolYear->id,
            ])
            ->with(
                'success',
                'Tarif mensuel enregistré avec succès.'
            );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(EcolageTarif $ecolageTarif)
    {
        $establishment = $this->currentEstablishment();

        if (
            $ecolageTarif->establishment_id
            !== $establishment->id
        ) {
            abort(403, 'Accès non autorisé à ce tarif.');
        }

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $ecolageTarif->school_year_id
        );

        $classes = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('nom_classe')
            ->get();

        return view('paiements.tarifs.edit', [
            'establishment' => $establishment,
            'schoolYear' => $schoolYear,
            'schoolYears' => $this->schoolYears($establishment),
            'classes' => $classes,
            'ecolageTarif' => $ecolageTarif,
            'tarif' => $ecolageTarif,
        ]);
    }

    /**
     * Met à jour un tarif.
     */
    public function update(
        Request $request,
        EcolageTarif $ecolageTarif
    ) {
        $establishment = $this->currentEstablishment();

        if (
            $ecolageTarif->establishment_id
            !== $establishment->id
        ) {
            abort(403, 'Accès non autorisé à ce tarif.');
        }

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $ecolageTarif->school_year_id
        );

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
                Rule::exists('class_rooms', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where(
                                'establishment_id',
                                $establishment->id
                            )
                            ->where(
                                'school_year_id',
                                $schoolYear->id
                            )
                    ),
            ],

            'montant_mensuel' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],
        ]);

        $duplicate = EcolageTarif::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->where(
                'class_room_id',
                $validated['class_room_id']
            )
            ->where(
                'id',
                '!=',
                $ecolageTarif->id
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'Un autre tarif existe déjà pour cette classe.',
                ]);
        }

        $ecolageTarif->update([
            'class_room_id' => $validated['class_room_id'],
            'montant_mensuel' => $validated['montant_mensuel'],
        ]);

        return redirect()
            ->route('paiements.tarifs.index', [
                'school_year_id' => $schoolYear->id,
            ])
            ->with(
                'success',
                'Tarif mensuel modifié avec succès.'
            );
    }

    /**
     * Supprime un tarif.
     */
    public function destroy(EcolageTarif $ecolageTarif)
    {
        $establishment = $this->currentEstablishment();

        if (
            $ecolageTarif->establishment_id
            !== $establishment->id
        ) {
            abort(403, 'Accès non autorisé à ce tarif.');
        }

        $schoolYearId = $ecolageTarif->school_year_id;

        $ecolageTarif->delete();

        return redirect()
            ->route('paiements.tarifs.index', [
                'school_year_id' => $schoolYearId,
            ])
            ->with(
                'success',
                'Tarif mensuel supprimé avec succès.'
            );
    }
}
