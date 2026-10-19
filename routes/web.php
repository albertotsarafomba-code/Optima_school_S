<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\SchoolTypeController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ResultatController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\SuiviEcolageController;
use App\Http\Controllers\DroitInscriptionController;
use App\Http\Controllers\EcolageTarifController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\EcolageMensuelController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\SuperAdminSetupController;
use App\Http\Controllers\SuperAdminConfigurationController;
use App\Http\Controllers\SuperAdminAbonnementController;
use App\Http\Controllers\AbonnementController;

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/* Configuration initiale, disponible une seule fois. */
Route::middleware('throttle:5,1')->group(function () {
    Route::get('/super-admin/setup', [SuperAdminSetupController::class, 'create'])
        ->name('super-admin.setup');

    Route::post('/super-admin/setup', [SuperAdminSetupController::class, 'store'])
        ->name('super-admin.setup.store');
});

Route::middleware('auth')->group(function () {

    /*
     * CONFIGURATION DE LA PLATEFORME — SUPER ADMIN UNIQUEMENT
     */
    Route::middleware('role:super_admin')
        ->prefix('super-admin/configuration')
        ->name('super-admin.configuration.')
        ->group(function () {
            Route::get(
                '/',
                [SuperAdminConfigurationController::class, 'index']
            )->name('index');

            Route::put(
                '/plateforme',
                [SuperAdminConfigurationController::class, 'updatePlatform']
            )->name('platform');

            Route::put(
                '/profil',
                [SuperAdminConfigurationController::class, 'updateProfile']
            )->name('profile');

            Route::put(
                '/mot-de-passe',
                [SuperAdminConfigurationController::class, 'updatePassword']
            )->name('password');
        });



    /*
     * GESTION DES ABONNEMENTS — SUPER ADMIN UNIQUEMENT
     */
    Route::middleware('role:super_admin')
        ->prefix('super-admin/abonnements')
        ->name('super-admin.abonnements.')
        ->group(function () {
            Route::get(
                '/',
                [SuperAdminAbonnementController::class, 'index']
            )->name('index');

            Route::get(
                '/etablissements/{establishment}',
                [SuperAdminAbonnementController::class, 'show']
            )->name('show');

            Route::post(
                '/etablissements/{establishment}/tarif',
                [SuperAdminAbonnementController::class, 'configurerTarif']
            )->name('tarif');

            Route::get(
                '/paiements/{paiement}/verification',
                [SuperAdminAbonnementController::class, 'verificationPaiement']
            )->name('paiements.verification');

            Route::get(
                '/paiements/{paiement}/justificatif/telecharger',
                [SuperAdminAbonnementController::class, 'telechargerJustificatif']
            )->name('paiements.justificatif.telecharger');

            Route::get(
                '/paiements/{paiement}/justificatif',
                [SuperAdminAbonnementController::class, 'voirJustificatif']
            )->name('paiements.justificatif');

            Route::post(
                '/paiements/{paiement}/valider',
                [SuperAdminAbonnementController::class, 'validerPaiement']
            )->name('paiements.valider');

            Route::post(
                '/paiements/{paiement}/rejeter',
                [SuperAdminAbonnementController::class, 'rejeterPaiement']
            )->name('paiements.rejeter');
        });

    /*
     * ABONNEMENT LOGICIEL — ADMIN DE SON ETABLISSEMENT
     */
    Route::middleware('role:admin')
        ->prefix('mon-abonnement')
        ->name('admin.abonnement.')
        ->group(function () {
            Route::get(
                '/',
                [AbonnementController::class, 'index']
            )->name('index');

            Route::post(
                '/paiements',
                [AbonnementController::class, 'store']
            )->name('paiements.store');
        });

    /*
     * ESPACE PARENT — CONSULTATION UNIQUEMENT
     */
    Route::middleware('role:parent')->group(function () {
        Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])
            ->name('parent.dashboard');

        Route::get('/parent/inscriptions', [ParentDashboardController::class, 'inscriptions'])
            ->name('parent.inscriptions');

        Route::get('/parent/paiements', [ParentDashboardController::class, 'paiements'])
            ->name('parent.paiements');
    });


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')->get(
        '/super-admin/dashboard',
        [SuperAdminSetupController::class, 'dashboard']
    )->name('super-admin.dashboard');

    Route::get('/dashboard', function () {
        if (auth()->user()?->role === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }

        return app(DashboardController::class)->index(request());
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ÉTABLISSEMENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/establishments/choose', [
        EstablishmentController::class,
        'choose'
    ])->name('establishments.choose');

    Route::get('/establishments/{establishment}/select', [
        EstablishmentController::class,
        'select'
    ])->name('establishments.select');


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATION DES ÉTABLISSEMENTS
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::resource(
            'establishments',
            EstablishmentController::class
        )->names('establishments');


        /*
        |--------------------------------------------------------------------------
        | TYPES D'ÉTABLISSEMENTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'school-types',
            SchoolTypeController::class
        )->names('school_types');


        /*
        |--------------------------------------------------------------------------
        | UTILISATEURS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'users',
            UserController::class
        )->names('users');

    });


    /*
    |--------------------------------------------------------------------------
    | ANNÉES SCOLAIRES
    |--------------------------------------------------------------------------
    */

    // Sélection d'une année scolaire
    Route::middleware(
        'role:admin,enseignant,parent'
    )->group(function () {

        Route::post(
            '/school-years/{school_year}/select',
            [
                SchoolYearController::class,
                'select'
            ]
        )->name('school_years.select');

    });

    // Gestion des années scolaires
    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'school-years',
            SchoolYearController::class
        )->names('school_years');

    });


    /*
    |--------------------------------------------------------------------------
    | ACTIVATION D'UNE ANNÉE SCOLAIRE
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::post(
            '/school-years/{school_year}/activate',
            [
                SchoolYearController::class,
                'activate'
            ]
        )->name('school_years.activate');

    });


    /*
    |--------------------------------------------------------------------------
    | ÉTUDIANTS
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'students',
            StudentController::class
        )->names('students');

    });


    /*
    |--------------------------------------------------------------------------
    | ENSEIGNANTS
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'teachers',
            TeacherController::class
        )->names('teachers');

    });


    /*
    |--------------------------------------------------------------------------
    | CLASSES
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'class-rooms',
            ClassRoomController::class
        )->names('class_rooms');


        /*
        |--------------------------------------------------------------------------
        | Matières d'une classe
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/class-rooms/{class_room}/subjects',
            [
                ClassRoomController::class,
                'subjects'
            ]
        )->name('class_rooms.subjects');


        Route::post(
            '/class-rooms/{class_room}/subjects',
            [
                ClassRoomController::class,
                'attachSubject'
            ]
        )->name('class_rooms.subjects.attach');


        Route::put(
            '/class-rooms/{class_room}/subjects/{subject}',
            [
                ClassRoomController::class,
                'updateSubject'
            ]
        )->name('class_rooms.subjects.update');


        Route::delete(
            '/class-rooms/{class_room}/subjects/{subject}',
            [
                ClassRoomController::class,
                'detachSubject'
            ]
        )->name('class_rooms.subjects.detach');

    });


    /*
    |--------------------------------------------------------------------------
    | SALLES
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'salles',
            SalleController::class
        )->names('salles');

    });


    /*
    |--------------------------------------------------------------------------
    | MATIÈRES
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'subjects',
            SubjectController::class
        )->names('subjects');

    });


    /*
    |--------------------------------------------------------------------------
    | AFFECTATION ENSEIGNANT / MATIÈRE
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::get(
            'teacher-subjects',
            [TeacherSubjectController::class, 'index']
        )->name('teacher_subjects.index');

        Route::get(
            'teacher-subjects/create',
            [TeacherSubjectController::class, 'create']
        )->name('teacher_subjects.create');

        Route::post(
            'teacher-subjects',
            [TeacherSubjectController::class, 'store']
        )->name('teacher_subjects.store');

        Route::delete(
            'teacher-subjects/{teacher_subject}',
            [TeacherSubjectController::class, 'destroy']
        )->name('teacher_subjects.destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | INSCRIPTIONS
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat'
    )->group(function () {

        Route::resource(
            'inscriptions',
            InscriptionController::class
        )->names('inscriptions');

    });


        /*
        |--------------------------------------------------------------------------
        | PARENTS
        |--------------------------------------------------------------------------
        |
        | Gestion des comptes parents et de leurs élèves.
        | Admin et secrétariat peuvent gérer les parents.
        |
        */

        Route::middleware('role:admin,secretariat')->group(function () {

            Route::resource('parents', ParentController::class)->names('parents');

        });


        /*
|--------------------------------------------------------------------------
| NOTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| NOTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Consultation NOTES + RÉSULTATS + EMPLOI DU TEMPS
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| CONSULTATION NOTES
|--------------------------------------------------------------------------
|
| Les Notes sont accessibles uniquement à :
| - Administrateur
| - Enseignant
| - Parent
|
| Le secrétariat n'a aucun accès aux Notes.
|--------------------------------------------------------------------------
*/

Route::middleware(
    'role:admin,enseignant,parent'
)->group(function () {

    Route::get(
        '/notes/classes-by-school-year',
        [
            NoteController::class,
            'classesBySchoolYear'
        ]
    )->name('notes.classes-by-school-year');

    Route::get(
        '/notes/students-by-class',
        [
            NoteController::class,
            'studentsByClass'
        ]
    )->name('notes.students-by-class');

    Route::get(
        '/notes/subjects-by-class',
        [
            NoteController::class,
            'subjectsByClass'
        ]
    )->name('notes.subjects-by-class');

    Route::get(
        '/notes/teachers-by-subject',
        [
            NoteController::class,
            'teachersBySubject'
        ]
    )->name('notes.teachers-by-subject');

    Route::get(
        '/notes',
        [
            NoteController::class,
            'index'
        ]
    )->name('notes.index');

    Route::get(
        '/notes/create',
        [
            NoteController::class,
            'create'
        ]
    )->middleware('role:admin,enseignant')->name('notes.create');

    Route::get(
        '/notes/{note}',
        [
            NoteController::class,
            'show'
        ]
    )->name('notes.show');

});


/*
|--------------------------------------------------------------------------
| RÉSULTATS — consultation uniquement
|--------------------------------------------------------------------------
*/

Route::middleware(
    'role:admin,enseignant,parent'
)->group(function () {

    Route::get(
        '/resultats',
        [
            ResultatController::class,
            'index'
        ]
    )->name('resultats.index');

});


/*
|--------------------------------------------------------------------------
| EMPLOI DU TEMPS — consultation uniquement
|--------------------------------------------------------------------------
|
| Le secrétariat doit pouvoir consulter l'emploi du temps.
|--------------------------------------------------------------------------
*/

Route::middleware(
    'role:admin,secretariat,enseignant,parent'
)->group(function () {

    Route::get(
        '/emploi-du-temps',
        [
            EmploiDuTempsController::class,
            'index'
        ]
    )->name('emploi-du-temps.index');

});


/*
|--------------------------------------------------------------------------
| GESTION NOTES
|--------------------------------------------------------------------------
|
| Admin, secrétariat et enseignant peuvent gérer les notes.
| Le parent reste en consultation uniquement.
|
*/

Route::middleware(
    'role:admin,enseignant'
)->group(function () {

    Route::post(
        '/notes',
        [
            NoteController::class,
            'store'
        ]
    )->name('notes.store');

    Route::get(
        '/notes/{note}/edit',
        [
            NoteController::class,
            'edit'
        ]
    )->name('notes.edit');

    Route::put(
        '/notes/{note}',
        [
            NoteController::class,
            'update'
        ]
    )->name('notes.update');

    Route::patch(
        '/notes/{note}',
        [
            NoteController::class,
            'update'
        ]
    );

    Route::delete(
        '/notes/{note}',
        [
            NoteController::class,
            'destroy'
        ]
    )->name('notes.destroy');

});


/*
|--------------------------------------------------------------------------
| EMPLOI DU TEMPS — gestion
|--------------------------------------------------------------------------
*/

Route::middleware(
    'role:admin,secretariat'
)->group(function () {

    Route::get(
        '/emploi-du-temps/create',
        [
            EmploiDuTempsController::class,
            'create'
        ]
    )->name('emploi-du-temps.create');

    Route::post(
        '/emploi-du-temps',
        [
            EmploiDuTempsController::class,
            'store'
        ]
    )->name('emploi-du-temps.store');

    Route::get(
        '/emploi-du-temps/{emploi_du_temp}/edit',
        [
            EmploiDuTempsController::class,
            'edit'
        ]
    )->name('emploi-du-temps.edit');

    Route::put(
        '/emploi-du-temps/{emploi_du_temp}',
        [
            EmploiDuTempsController::class,
            'update'
        ]
    )->name('emploi-du-temps.update');

    Route::patch(
        '/emploi-du-temps/{emploi_du_temp}',
        [
            EmploiDuTempsController::class,
            'update'
        ]
    );

    Route::delete(
        '/emploi-du-temps/{emploi_du_temp}',
        [
            EmploiDuTempsController::class,
            'destroy'
        ]
    )->name('emploi-du-temps.destroy');

});


    /*
    |--------------------------------------------------------------------------
    | PAIEMENTS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SUIVI ÉCOLAGE / PAIEMENTS
    |--------------------------------------------------------------------------
    |
    | Le nouveau suivi financier utilise SuiviEcolageController.
    | Les opérations anciennes edit/update/delete restent confiées
    | à PaiementController afin de préserver les fonctionnalités existantes.
    |
    */

    Route::middleware(
        'role:admin,comptable'
    )->group(function () {

        /*
         * ---------------------------------------------------------
         * GESTION DES TARIFS D'ÉCOLAGE
         * ---------------------------------------------------------
         */

        Route::get(
            '/paiements/tarifs',
            [
                EcolageTarifController::class,
                'index'
            ]
        )->name('paiements.tarifs.index');

        Route::get(
            '/paiements/tarifs/create',
            [
                EcolageTarifController::class,
                'create'
            ]
        )->name('paiements.tarifs.create');

        Route::post(
            '/paiements/tarifs',
            [
                EcolageTarifController::class,
                'store'
            ]
        )->name('paiements.tarifs.store');

        Route::get(
            '/paiements/tarifs/{ecolageTarif}/edit',
            [
                EcolageTarifController::class,
                'edit'
            ]
        )->name('paiements.tarifs.edit');

        Route::put(
            '/paiements/tarifs/{ecolageTarif}',
            [
                EcolageTarifController::class,
                'update'
            ]
        )->name('paiements.tarifs.update');

        Route::delete(
            '/paiements/tarifs/{ecolageTarif}',
            [
                EcolageTarifController::class,
                'destroy'
            ]
        )->name('paiements.tarifs.destroy');


        /*
         * Tableau principal du suivi écolage.
         */
        Route::get(
            '/paiements',
            [
                SuiviEcolageController::class,
                'index'
            ]
        )->name('paiements.index');

        /*
         * Formulaire d'enregistrement d'un écolage.
         */
        Route::get(
            '/paiements/create',
            [
                SuiviEcolageController::class,
                'create'
            ]
        )->name('paiements.create');

        /*
         * Enregistrement :
         * Écolage mensuel + Espèces uniquement.
         */
        Route::post(
            '/paiements',
            [
                SuiviEcolageController::class,
                'store'
            ]
        )->name('paiements.store');

        /*
         * ---------------------------------------------------------
         * DROITS D'INSCRIPTION
         * ---------------------------------------------------------
         */

        Route::get(
            '/paiements/droits-inscription',
            [DroitInscriptionController::class, 'index']
        )->name('paiements.droits.index');

        Route::get(
            '/paiements/droits-inscription/create',
            [DroitInscriptionController::class, 'create']
        )->name('paiements.droits.create');

        Route::post(
            '/paiements/droits-inscription',
            [DroitInscriptionController::class, 'store']
        )->name('paiements.droits.store');

        Route::get(
            '/paiements/droits-inscription/{paiement}/recu',
            [DroitInscriptionController::class, 'receipt']
        )->whereNumber('paiement')
         ->name('paiements.droits.recu');

        Route::get(
            '/paiements/droits-inscription/{paiement}',
            [DroitInscriptionController::class, 'show']
        )->whereNumber('paiement')
         ->name('paiements.droits.show');


        /*
         * Détail d'un paiement.
         */
        Route::get(
            '/paiements/{paiement}',
            [
                SuiviEcolageController::class,
                'show'
            ]
        )->name('paiements.show');

        /*
         * Reçu / fiche de paiement.
         */
        Route::get(
            '/paiements/{paiement}/recu',
            [
                SuiviEcolageController::class,
                'receipt'
            ]
        )->name('paiements.recu');

        /*
         * Anciennes fonctions conservées.
         */
        Route::get(
            '/paiements/{paiement}/edit',
            [
                PaiementController::class,
                'edit'
            ]
        )->name('paiements.edit');

        Route::put(
            '/paiements/{paiement}',
            [
                PaiementController::class,
                'update'
            ]
        )->name('paiements.update');

        Route::patch(
            '/paiements/{paiement}',
            [
                PaiementController::class,
                'update'
            ]
        );

        Route::delete(
            '/paiements/{paiement}',
            [
                PaiementController::class,
                'destroy'
            ]
        )->name('paiements.destroy');

        /*
         * Ancien écran écolages mensuels conservé.
         */
        Route::get(
            '/ecolages-mensuels',
            [
                EcolageMensuelController::class,
                'index'
            ]
        )->name('ecolages-mensuels.index');

    });


    /*
    |--------------------------------------------------------------------------
    | ROUTES UTILITAIRES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/after-login',
        function () {

            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login');
            }

            if ($user->role === 'super_admin') {
                return redirect()->route('super-admin.dashboard');
            }

            // Les parents utilisent leur espace dédié, strictement consultatif.
            if ($user->role === 'parent') {
                return redirect()->route('parent.dashboard');
            }

            return redirect()->route('dashboard');

        }
    )->name('after-login');


/*
|--------------------------------------------------------------------------
| BULLETINS
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin,enseignant,parent')->group(function () {

    Route::get(
        '/bulletins',
        [
            BulletinController::class,
            'index'
        ]
    )->name('bulletins.index');

    Route::get(
        '/bulletins/{student}',
        [
            BulletinController::class,
            'show'
        ]
    )->name('bulletins.show');

});

});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
|
| Laravel Fortify gère les routes login/register/logout.
|
*/
require __DIR__.'/settings.php';
require __DIR__.'/settings.php';
