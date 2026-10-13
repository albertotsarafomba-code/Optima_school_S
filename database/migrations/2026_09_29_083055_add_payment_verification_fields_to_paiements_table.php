<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {

            $table->string('statut_verification')
                ->default('À vérifier')
                ->after('description');

            $table->string('preuve_paiement')
                ->nullable()
                ->after('statut_verification');

            $table->string('preuve_type')
                ->nullable()
                ->after('preuve_paiement');

            $table->foreignId('verifie_par')
                ->nullable()
                ->after('preuve_type')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verifie_le')
                ->nullable()
                ->after('verifie_par');

            $table->text('note_verification')
                ->nullable()
                ->after('verifie_le');
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {

            $table->dropForeign(['verifie_par']);

            $table->dropColumn([
                'statut_verification',
                'preuve_paiement',
                'preuve_type',
                'verifie_par',
                'verifie_le',
                'note_verification',
            ]);
        });
    }
};
