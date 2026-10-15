<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('establishment_id')
                ->unique()
                ->constrained('establishments')
                ->restrictOnDelete();

            $table->date('date_debut')->nullable();
            $table->date('date_expiration')->nullable();

            // Le tarif mensuel sera défini ultérieurement.
            $table->decimal('montant_mensuel', 12, 2)->nullable();

            $table->string('devise', 3)->default('MGA');
            $table->string('statut', 20)->default('inactif')->index();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
