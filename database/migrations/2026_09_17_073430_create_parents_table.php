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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();

            /*
             * Compte utilisateur associé au parent.
             *
             * Nullable pour permettre de conserver un profil
             * parent même si aucun compte de connexion n'est
             * encore associé.
             */
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            /*
             * Établissement auquel appartient le parent.
             */
            $table->foreignId('establishment_id')
                ->constrained('establishments')
                ->cascadeOnDelete();

            $table->string('nom');
            $table->string('prenom')->nullable();

            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
