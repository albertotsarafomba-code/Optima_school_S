<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Supprimer l'ancien index unique global sur le code
            $table->dropUnique('subjects_code_unique');

            // Le code devient unique par établissement,
            // année scolaire et type d'établissement.
            $table->unique(
                [
                    'establishment_id',
                    'school_year_id',
                    'school_type_id',
                    'code',
                ],
                'subjects_establishment_year_type_code_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropUnique(
                'subjects_establishment_year_type_code_unique'
            );

            $table->unique(
                'code',
                'subjects_code_unique'
            );
        });
    }
};
