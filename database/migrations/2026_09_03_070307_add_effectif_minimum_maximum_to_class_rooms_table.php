<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter l'effectif minimum et maximum
     * pour chaque salle/classe.
     */
    public function up(): void
    {
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->unsignedInteger('effectif_minimum')
                ->nullable()
                ->after('capacite');

            $table->unsignedInteger('effectif_maximum')
                ->nullable()
                ->after('effectif_minimum');
        });
    }

    /**
     * Supprimer les champs ajoutés.
     */
    public function down(): void
    {
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->dropColumn([
                'effectif_minimum',
                'effectif_maximum',
            ]);
        });
    }
};
