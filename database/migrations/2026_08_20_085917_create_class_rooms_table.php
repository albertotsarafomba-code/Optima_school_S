<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();

            $table->string('nom_classe');
            $table->string('niveau');
            $table->string('filiere');
            $table->string('annee_scolaire');
            $table->string('salle')->nullable();
            $table->integer('capacite')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
