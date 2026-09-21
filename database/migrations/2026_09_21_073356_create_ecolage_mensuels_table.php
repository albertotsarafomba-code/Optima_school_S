<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des écolages mensuels
     */
    public function up(): void
    {
        Schema::create('ecolage_mensuels', function (Blueprint $table) {
            $table->id();

            /*
             * Établissement concerné
             */
            $table->foreignId('establishment_id')
                ->constrained('establishments')
                ->cascadeOnDelete();

            /*
             * Année scolaire concernée
             */
            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            /*
             * Étudiant concerné
             */
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            /*
             * Montant mensuel individuel
             */
            $table->decimal('montant_mensuel', 12, 2);

            $table->timestamps();

            /*
             * Un seul tarif mensuel par étudiant
             * pour une année scolaire donnée.
             */
            $table->unique(
                ['school_year_id', 'student_id'],
                'ecolage_mensuels_school_year_student_unique'
            );

            /*
             * Index utile pour les recherches
             * par établissement.
             */
            $table->index('establishment_id');
        });
    }

    /**
     * Suppression de la table
     */
    public function down(): void
    {
        Schema::dropIfExists('ecolage_mensuels');
    }
};
