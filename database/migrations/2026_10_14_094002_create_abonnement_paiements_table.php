<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnement_paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('abonnement_id')
                ->constrained('abonnements')
                ->restrictOnDelete();

            $table->decimal('montant', 12, 2);
            $table->string('mode_paiement', 40);
            $table->string('reference_transaction', 150)->nullable();

            // Une preuve ne suffit jamais à confirmer un paiement.
            $table->string('preuve_paiement')->nullable();

            $table->string('statut', 20)->default('en_attente')->index();

            $table->foreignId('verifie_par')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('date_soumission')->nullable();
            $table->timestamp('date_paiement')->nullable();
            $table->timestamp('date_verification')->nullable();

            $table->text('note_verification')->nullable();

            $table->timestamps();

            $table->index(
                ['abonnement_id', 'created_at'],
                'abonnement_paiements_historique_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnement_paiements');
    }
};
