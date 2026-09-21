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
            $table->foreignId('school_type_id')
                ->nullable()
                ->after('id')
                ->constrained('school_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['establishment_id']);
            $table->dropColumn('establishment_id');
            $table->dropColumn('coefficient');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->index(
                ['school_type_id', 'code'],
                'subjects_school_type_code_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(
                'subjects_school_type_code_index'
            );
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['school_type_id']);
            $table->dropColumn('school_type_id');

            $table->foreignId('establishment_id')
                ->nullable()
                ->after('id')
                ->constrained('establishments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->integer('coefficient')
                ->nullable()
                ->after('description');
        });
    }
};
