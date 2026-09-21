<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->constrained('class_rooms')
                ->cascadeOnDelete();

            $table->string('annee_scolaire');
            $table->date('date_inscription');
            $table->string('statut')->default('Active');

            $table->timestamps();

            $table->unique([
                'student_id',
                'annee_scolaire',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};