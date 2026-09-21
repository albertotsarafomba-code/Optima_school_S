<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\SchoolYear;
use App\Models\Establishment;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    /**
     * Récupérer l'établissement actuellement sélectionné.
     */
    private function establishmentId()
    {
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            return null;
        }

        $establishment = auth()->user()
            ->establishments()
            ->where('id', $establishmentId)
            ->first();

        if (!$establishment) {
            session()->forget([
                'establishment_id',
                'establishment_name',
            ]);

            return null;
        }

        return $establishment->id;
    }

    /**
     * Liste des inscriptions.
     */
    public function index()
    {
        $establishmentId = $this->establishmentId();

        if (!$establishmentId) {
            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Veuillez sélectionner un établissement.'
                );
        }

        $inscriptions = Inscription::with([
            'student',
            'classRoom',
            'schoolYear',
        ])
            ->where('establishment_id', $establishmentId)
            ->latest()
            ->get();

        return view(
            'inscriptions.index',
            compact('inscriptions')
        );
    }

    
/**
 * Formulaire de création d'une inscription.
 */
public function create()
{
    $establishmentId = $this->establishmentId();

    if (!$establishmentId) {
        return redirect()
            ->route('establishments.choose')
            ->with(
                'error',
                'Veuillez sélectionner un établissement.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Établissement actif + type
    |--------------------------------------------------------------------------
    */
    $establishment = Establishment::with('schoolType')
        ->findOrFail($establishmentId);

    $schoolType = $establishment->schoolType;

    $schoolTypeName = $schoolType
        ? $schoolType->name
        : null;

    /*
    |--------------------------------------------------------------------------
    | Années scolaires de l'établissement
    |--------------------------------------------------------------------------
    */
    $schoolYears = SchoolYear::where(
        'establishment_id',
        $establishmentId
    )
        ->orderByDesc('start_date')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Année scolaire active
    |--------------------------------------------------------------------------
    */
    $activeSchoolYear = $schoolYears
        ->firstWhere('is_active', true);

    $selectedSchoolYearId = session(
        'school_year_id',
        $activeSchoolYear?->id
    );

    /*
    |--------------------------------------------------------------------------
    | Vérifier que l'année appartient à l'établissement
    |--------------------------------------------------------------------------
    */
    if (
        $selectedSchoolYearId &&
        !$schoolYears->contains('id', $selectedSchoolYearId)
    ) {
        $selectedSchoolYearId = $activeSchoolYear?->id;
    }


    /*
|--------------------------------------------------------------------------
| Étudiants de l'établissement
|--------------------------------------------------------------------------
*/
$students = Student::where(
    'establishment_id',
    $establishmentId
)
    ->orderBy('nom')
    ->orderBy('prenom')
    ->get();

    /*
    |--------------------------------------------------------------------------
    | Classes de l'établissement + année scolaire
    |--------------------------------------------------------------------------
    */
    $classes = collect();

    if ($selectedSchoolYearId) {
        $classes = ClassRoom::where(
            'establishment_id',
            $establishmentId
        )
            ->where(
                'school_year_id',
                $selectedSchoolYearId
            )
            ->orderBy('niveau')
            ->orderBy('nom_classe')
            ->get();
    }

    return view(
        'inscriptions.create',
         compact(
    'students',
    'classes',
    'schoolYears',
    'establishment',
    'schoolType',
    'schoolTypeName',
    'selectedSchoolYearId'
)
    );
}


/**
 * Enregistrer une nouvelle inscription
 * ET créer automatiquement le nouvel étudiant.
 */
public function store(Request $request)
{
    $establishmentId = $this->establishmentId();

    if (!$establishmentId) {
        return redirect()
            ->route('establishments.choose')
            ->with(
                'error',
                'Veuillez sélectionner un établissement.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Établissement actif
    |--------------------------------------------------------------------------
    */
    $establishment = Establishment::with('schoolType')
        ->findOrFail($establishmentId);

    /*
    |--------------------------------------------------------------------------
    | Validation étudiant
    |--------------------------------------------------------------------------
    */
    $validated = $request->validate([
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

        'date_naissance' => [
            'required',
            'date',
        ],

        'lieu_naissance' => [
            'nullable',
            'string',
            'max:255',
        ],

        'sexe' => [
            'required',
            'in:Masculin,Féminin',
        ],

        'adresse' => [
            'nullable',
            'string',
            'max:255',
        ],

        'telephone' => [
            'nullable',
            'string',
            'max:30',
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
        ],

        /*
        |--------------------------------------------------------------------------
        | Inscription
        |--------------------------------------------------------------------------
        */
        'class_room_id' => [
            'required',
            'exists:class_rooms,id',
        ],

        'school_year_id' => [
            'required',
            'exists:school_years,id',
        ],

        'date_inscription' => [
            'required',
            'date',
        ],

        'statut' => [
            'required',
            'in:Active,Inactive',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Vérifier l'année scolaire
    |--------------------------------------------------------------------------
    */
    $schoolYear = SchoolYear::where(
        'id',
        $validated['school_year_id']
    )
        ->where(
            'establishment_id',
            $establishmentId
        )
        ->first();

    if (!$schoolYear) {
        abort(
            403,
            'Cette année scolaire n\'appartient pas à l\'établissement sélectionné.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Vérifier la classe
    |--------------------------------------------------------------------------
    */
    $classRoom = ClassRoom::where(
        'id',
        $validated['class_room_id']
    )
        ->where(
            'establishment_id',
            $establishmentId
        )
        ->where(
            'school_year_id',
            $schoolYear->id
        )
        ->first();

    if (!$classRoom) {
        abort(
            403,
            'Cette classe n\'appartient pas à l\'établissement ou à l\'année scolaire sélectionnée.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Vérifier l'effectif maximum
    |--------------------------------------------------------------------------
    */
    if ($validated['statut'] === 'Active') {

        $effectifActuel = Inscription::where(
            'establishment_id',
            $establishmentId
        )
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->where(
                'statut',
                'Active'
            )
            ->count();

        if (
            $classRoom->effectif_maximum !== null &&
            $effectifActuel >= $classRoom->effectif_maximum
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        "La classe {$classRoom->nom_classe} est complète. " .
                        "Effectif maximum : {$classRoom->effectif_maximum}.",
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Création du nouvel étudiant
    |--------------------------------------------------------------------------
    */
    $student = Student::create([
        'establishment_id' => $establishmentId,
        'nom' => $validated['nom'],
        'prenom' => $validated['prenom'],
        'date_naissance' => $validated['date_naissance'],
        'lieu_naissance' => $validated['lieu_naissance'] ?? null,
        'sexe' => $validated['sexe'],
        'adresse' => $validated['adresse'] ?? null,
        'telephone' => $validated['telephone'] ?? null,
        'email' => $validated['email'] ?? null,

        /*
        | On garde aussi la classe dans Student
        | pour compatibilité avec la structure actuelle.
        */
        'classe' => $classRoom->nom_classe,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Création de l'inscription
    |--------------------------------------------------------------------------
    */
    Inscription::create([
        'establishment_id' => $establishmentId,
        'student_id' => $student->id,
        'class_room_id' => $classRoom->id,
        'school_year_id' => $schoolYear->id,
        'annee_scolaire' => $schoolYear->name,
        'date_inscription' => $validated['date_inscription'],
        'statut' => $validated['statut'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Retour vers la liste des étudiants
    |--------------------------------------------------------------------------
    */
    return redirect()
        ->route('students.index')
        ->with(
            'success',
            'Étudiant inscrit avec succès.'
        );
}    
}