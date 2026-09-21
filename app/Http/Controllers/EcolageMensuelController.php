<?php

namespace App\Http\Controllers;

use App\Models\EcolageMensuel;
use App\Models\Paiement;
use Illuminate\Http\Request;

class EcolageMensuelController extends Controller
{
    /**
     * Afficher le suivi mensuel des écolages.
     */
    public function index(Request $request)
    {
        $establishmentId = session('establishment_id');
        $schoolYearId = session('school_year_id');

        if (!$establishmentId) {
            abort(403, 'Aucun établissement sélectionné.');
        }

        if (!$schoolYearId) {
            abort(403, 'Aucune année scolaire sélectionnée.');
        }

        $mois = (int) ($request->input('mois') ?? now()->month);

        $annee = (int) (
            $request->input('annee')
            ?? now()->year
        );

        if ($mois < 1 || $mois > 12) {
            $mois = now()->month;
        }

        $ecolages = EcolageMensuel::query()
            ->with([
                'student',
                'schoolYear',
                'establishment',
            ])
            ->where('establishment_id', $establishmentId)
            ->where('school_year_id', $schoolYearId)
            ->get();

        $suivi = $ecolages->map(function ($ecolage) use ($mois, $annee) {

            $paye = Paiement::query()
                ->where('establishment_id', $ecolage->establishment_id)
                ->where('student_id', $ecolage->student_id)
                ->where('annee', $annee)
                ->where('mois', $mois)
                ->where(function ($query) {
                    $query
                        ->where('type_paiement', 'Écolage mensuel')
                        ->orWhere('type_paiement', 'Ecolage mensuel')
                        ->orWhere('type_paiement', 'écolage mensuel')
                        ->orWhere('type_paiement', 'ecolage mensuel');
                })
                ->sum('montant');

            $prevu = (float) $ecolage->montant_mensuel;
            $paye = (float) $paye;

            $reste = max(0, $prevu - $paye);

            if ($paye >= $prevu) {
                $statut = 'Payé';
            } elseif ($paye > 0) {
                $statut = 'Partiel';
            } else {
                $statut = 'Impayé';
            }

            return [
                'ecolage' => $ecolage,
                'student' => $ecolage->student,
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
            compact(
                'suivi',
                'mois',
                'annee'
            )
        );
    }
}