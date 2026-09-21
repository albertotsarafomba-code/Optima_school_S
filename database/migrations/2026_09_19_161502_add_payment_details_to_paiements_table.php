<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter les informations nécessaires
     * à la gestion complète des paiements scolaires.
     */
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {

            // Établissement concerné par le paiement
            $table->foreignId('establishment_id')
                ->nullable()
                ->after('id')
                ->constrained('establishments')
                ->nullOnDelete();

            // Année scolaire concernée
            $table->foreignId('school_year_id')
                ->nullable()
                ->after('establishment_id')
                ->constrained('school_years')
                ->nullOnDelete();

            // Enseignant concerné
            // Utilisé notamment pour "Cotisation enseignant"
            $table->foreignId('teacher_id')
                ->nullable()
                ->after('student_id')
                ->constrained('teachers')
                ->nullOnDelete();

            // Mois concerné par l'écolage
            // Exemple : 9 = septembre
            $table->unsignedTinyInteger('mois')
                ->nullable()
                ->after('type_paiement');

            // Année civile du mois payé
            // Exemple : 2027
            $table->unsignedSmallInteger('annee')
                ->nullable()
                ->after('mois');

            // Numéro unique du reçu
            $table->string('numero_recu')
                ->nullable()
                ->unique()
                ->after('reference');
        });
    }

    /**
     * Annuler les modifications.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {

            $table->dropForeign([
                'establishment_id',
            ]);

            $table->dropForeign([
                'school_year_id',
            ]);

            $table->dropForeign([
                'teacher_id',
            ]);

            $table->dropUnique([
                'paiements_numero_recu_unique',
            ]);

            $table->dropColumn([
                'establishment_id',
                'school_year_id',
                'teacher_id',
                'mois',
                'annee',
                'numero_recu',
            ]);
        });
    }
};