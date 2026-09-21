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
            $table->foreignId('establishment_id')
                ->nullable()
                ->after('id')
                ->constrained('establishments')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->nullable()
                ->after('establishment_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            $table->index(
                ['establishment_id', 'school_year_id'],
                'subjects_establishment_school_year_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign([
                'establishment_id',
            ]);

            $table->dropForeign([
                'school_year_id',
            ]);

            $table->dropIndex(
                'subjects_establishment_school_year_index'
            );

            $table->dropColumn([
                'establishment_id',
                'school_year_id',
            ]);
        });
    }
};
