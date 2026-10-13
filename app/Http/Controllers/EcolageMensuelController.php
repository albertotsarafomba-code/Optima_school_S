<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\EcolageTarif;
use Illuminate\Http\Request;

class EcolageMensuelController extends Controller
{
    /**
     * Vérifie l'accès à l'établissement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(403, 'Aucun établissement sélectionné.');
        }

        $user = auth()->user();

        if (!$user) {
            abort(403, 'Utilisateur non authentifié.');
        }

        if ($user->role === 'admin') {
            $establishment = Establishment::find($establishmentId);
        } else {
            $establishment = $user->assignedEstablishments()
                ->where('establishments.id', $establishmentId)
                ->first();
        }

        if (!$establishment) {
            abort(403, 'Vous n’avez pas accès à cet établissement.');
        }

        return $establishment;
    }

    /**
     * Vérifie que l'année scolaire appartient à l'établissement.
     */
    private function currentSchoolYear(Establishment $establishment): SchoolYear
    {
        $schoolYearId = session('school_year_id');

        if (!$schoolYearId) {
            $schoolYearId = SchoolYear::query()
                ->where('establishment_id', $establishment->id)
                ->where('is_active', true)
                ->orderByDesc('id')
                ->value('id');
        }

        if (!$schoolYearId) {
            abort(404, 'Aucune année scolaire disponible.');
        }

        $schoolYear = SchoolYear::query()
            ->whereKey($schoolYearId)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$schoolYear) {
            abort(403, 'Cette année scolaire n’appartient pas à cet établissement.');
        }

        return $schoolYear;
    }

    /**
     * Afficher le suivi mensuel des écolages.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();
        $schoolYear = $this->currentSchoolYear($establishment);

        $establishmentId = (int) $establishment->id;
        $schoolYearId = (int) $schoolYear->id;

        $mois = (int) ($request->input('mois') ?? now()->month);
        $annee = (int) ($request->input('annee') ?? now()->year);

        if ($mois < 1 || $mois > 12) {
            $mois = now()->month;
        }

        if ($annee < 2000 || $annee > 2100) {
            $annee = now()->year;
        }

        /*
         * Récupérer les inscriptions actives de l'établissement
         * et de l'année scolaire sélectionnés.
         */
        $inscriptions = Inscription::query()
            ->with(['student', 'classRoom'])
            ->where('establishment_id', $establishmentId)
            ->where('school_year_id', $schoolYearId)
            ->where('statut', 'Actif')
            ->get();

        /*
         * Charger les tarifs par classe pour éviter de répéter
         * la même requête pour chaque élève.
         */
        $tarifs = EcolageTarif::query()
            ->where('establishment_id', $establishmentId)
            ->where('school_year_id', $schoolYearId)
            ->get()
            ->keyBy('class_room_id');

        /*
         * Calculer le montant payé pour chaque élève.
         * Les paiements sont regroupés par élève pour le mois choisi.
         */
        $paiements = Paiement::query()
            ->where('establishment_id', $establishmentId)
            ->where('school_year_id', $schoolYearId)
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->whereIn('type_paiement', [
                'Écolage mensuel',
                'Ecolage mensuel',
                'écolage mensuel',
                'ecolage mensuel',
            ])
            ->selectRaw('student_id, SUM(montant) as total_paye')
            ->groupBy('student_id')
            ->pluck('total_paye', 'student_id');

        $suivi = $inscriptions->map(function ($inscription) use (
            $tarifs,
            $paiements,
            $mois,
            $annee
        ) {
            $student = $inscription->student;
            $classRoomId = $inscription->class_room_id;

            $tarif = $tarifs->get($classRoomId);

            $prevu = $tarif !== null
                ? (float) $tarif->montant_mensuel
                : null;

            $paye = (float) ($paiements[$inscription->student_id] ?? 0);

            if ($prevu === null) {
                $reste = null;
                $statut = 'Tarif non défini';
            } else {
                $reste = max(0, $prevu - $paye);

                if ($paye >= $prevu) {
                    $statut = 'Payé';
                } elseif ($paye > 0) {
                    $statut = 'Partiel';
                } else {
                    $statut = 'Impayé';
                }
            }

            return [
                'inscription' => $inscription,
                'ecolage' => $tarif,
                'student' => $student,
                'classRoom' => $inscription->classRoom,
                'mois' => $mois,
                'annee' => $annee,
                'prevu' => $prevu,
                'paye' => $paye,
                'reste' => $reste,
                'statut' => $statut,
            ];
        });

        return view(
            'ecolages_mensuels.index',
            compact('suivi', 'mois', 'annee')
        );
    }
}
