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
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                ->nullable()
                ->after('establishment_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('class_rooms')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->nullable()
                ->after('class_room_id')
                ->constrained('school_years')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['class_room_id']);
            $table->dropForeign(['school_year_id']);

            $table->dropColumn([
                'teacher_id',
                'class_room_id',
                'school_year_id',
            ]);
        });
    }
};
