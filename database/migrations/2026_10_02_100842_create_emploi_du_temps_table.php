<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emploi_du_temps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('establishment_id')
                ->constrained('establishments')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->constrained('class_rooms')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignId('salle_id')
                ->constrained('salles')
                ->cascadeOnDelete();

            $table->string('jour', 20);

            $table->time('heure_debut');

            $table->time('heure_fin');

            $table->timestamps();

            $table->index(
                ['establishment_id', 'school_year_id', 'class_room_id'],
                'edt_etab_annee_classe_idx'
            );

            $table->index(
                ['teacher_id', 'school_year_id'],
                'edt_enseignant_annee_idx'
            );

            $table->index(
                ['salle_id', 'school_year_id'],
                'edt_salle_annee_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};
