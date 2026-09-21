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
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\EcolageMensuelController;

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ÉTABLISSEMENTS
    |--------------------------------------------------------------------------
    |
    | Consultation / sélection :
    | admin + utilisateurs affectés.
    |
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
    |
    | Tous les utilisateurs autorisés peuvent consulter/sélectionner
    | une année scolaire.
    |
    */

    Route::middleware(
        'role:admin,secretariat,enseignant,eleve,parent'
    )->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Sélection de l'année scolaire
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/school-years/{school_year}/select',
            [
                SchoolYearController::class,
                'select'
            ]
        )->name('school_years.select');


        /*
        |--------------------------------------------------------------------------
        | CRUD années scolaires
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'school-years',
            SchoolYearController::class
        )->names('school_years');
    });


    /*
    |--------------------------------------------------------------------------
    | ACTIVATION D'UNE ANNÉE SCOLAIRE
    |--------------------------------------------------------------------------
    |
    | Seul l'administrateur peut activer une année scolaire.
    |
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
        'role:admin,secretariat,enseignant,eleve,parent'
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
        'role:admin,secretariat,enseignant,eleve,parent'
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
        'role:admin,secretariat,enseignant,eleve,parent'
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
        'role:admin,secretariat,enseignant,eleve,parent'
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

        Route::resource(
            'teacher-subjects',
            TeacherSubjectController::class
        )->names('teacher_subjects');
    });


    /*
    |--------------------------------------------------------------------------
    | INSCRIPTIONS
    |--------------------------------------------------------------------------
    |
    | Gestion administrative.
    |
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
    | NOTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:admin,secretariat,enseignant,eleve,parent'
    )->group(function () {

        Route::resource(
            'notes',
            NoteController::class
        )->names('notes');


        /*
        |--------------------------------------------------------------------------
        | Classes selon année scolaire
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/notes/classes-by-school-year',
            [
                NoteController::class,
                'classesBySchoolYear'
            ]
        )->name('notes.classes-by-school-year');


        /*
        |--------------------------------------------------------------------------
        | Étudiants selon classe
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/notes/students-by-class',
            [
                NoteController::class,
                'studentsByClass'
            ]
        )->name('notes.students-by-class');
    });


    /*
|--------------------------------------------------------------------------
| PAIEMENTS
|--------------------------------------------------------------------------
*/

Route::middleware(
    'role:admin,secretariat,eleve,parent'
)->group(function () {

    Route::resource(
        'paiements',
        PaiementController::class
    )->names('paiements');

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

            return redirect()->route('dashboard');
        }
    )->name('after-login');
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
|
| Laravel Fortify gère les routes login/register/logout.
|
*/

