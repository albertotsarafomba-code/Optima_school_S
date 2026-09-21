<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_years', function (Blueprint $table) {
            // Esorina ilay unique global amin'ny name
            $table->dropUnique('school_years_name_unique');

            // Unique par établissement
            $table->unique(
                ['establishment_id', 'name'],
                'school_years_establishment_name_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('school_years', function (Blueprint $table) {
            // Esorina ilay unique par établissement
            $table->dropUnique(
                'school_years_establishment_name_unique'
            );

            // Averina ilay unique global
            $table->unique(
                'name',
                'school_years_name_unique'
            );
        });
    }
};
