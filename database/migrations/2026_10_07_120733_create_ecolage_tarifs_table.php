<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création des tarifs mensuels d'écolage.
     */
    public function up(): void
    {
        Schema::create('ecolage_tarifs', function (Blueprint $table) {
            $table->id();

            /*
             * Établissement concerné.
             */
            $table->foreignId('establishment_id')
                ->constrained('establishments')
                ->cascadeOnDelete();

            /*
             * Année scolaire concernée.
             */
            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            /*
             * Classe concernée.
             */
            $table->foreignId('class_room_id')
                ->constrained('class_rooms')
                ->cascadeOnDelete();

            /*
             * Montant mensuel de l'écolage en Ariary.
             */
            $table->decimal('montant_mensuel', 12, 2);

            $table->timestamps();

            /*
             * Une seule tarification par établissement,
             * année scolaire et classe.
             */
            $table->unique(
                [
                    'establishment_id',
                    'school_year_id',
                    'class_room_id',
                ],
                'ecolage_tarifs_unique'
            );

            /*
             * Index utiles pour le suivi comptable.
             */
            $table->index(
                ['establishment_id', 'school_year_id'],
                'ecolage_tarifs_establishment_school_year_index'
            );

            $table->index(
                'class_room_id',
                'ecolage_tarifs_class_room_index'
            );
        });
    }

    /**
     * Suppression de la table.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecolage_tarifs');
    }
};
