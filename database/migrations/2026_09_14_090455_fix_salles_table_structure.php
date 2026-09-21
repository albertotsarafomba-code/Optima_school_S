<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter les colonnes manquantes à la table salles.
     */
    public function up(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            $table->foreignId('establishment_id')
                ->after('id')
                ->constrained('establishments')
                ->cascadeOnDelete();

            $table->string('nom')
                ->after('establishment_id');

            $table->unsignedInteger('capacite')
                ->nullable()
                ->after('nom');

            $table->text('description')
                ->nullable()
                ->after('capacite');

            $table->unique([
                'establishment_id',
                'nom',
            ]);
        });

        /*
         * Ajouter salle_id à class_rooms.
         *
         * La colonne "salle" existante n'est pas supprimée.
         * Elle est conservée pour ne pas casser les anciennes données.
         */
        if (! Schema::hasColumn('class_rooms', 'salle_id')) {
            Schema::table('class_rooms', function (Blueprint $table) {
                $table->foreignId('salle_id')
                    ->nullable()
                    ->after('school_year_id')
                    ->constrained('salles')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Annuler la migration.
     */
    public function down(): void
    {
        if (Schema::hasColumn('class_rooms', 'salle_id')) {
            Schema::table('class_rooms', function (Blueprint $table) {
                $table->dropForeign(['salle_id']);
                $table->dropColumn('salle_id');
            });
        }

        if (Schema::hasColumn('salles', 'establishment_id')) {
            Schema::table('salles', function (Blueprint $table) {
                $table->dropUnique([
                    'establishment_id',
                    'nom',
                ]);

                $table->dropForeign([
                    'establishment_id',
                ]);

                $table->dropColumn([
                    'establishment_id',
                    'nom',
                    'capacite',
                    'description',
                ]);
            });
        }
    }
};
