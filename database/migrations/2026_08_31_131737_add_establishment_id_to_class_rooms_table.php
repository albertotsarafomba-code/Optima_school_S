<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter l'établissement à chaque classe.
     */
    public function up(): void
    {
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->foreignId('establishment_id')
                ->nullable()
                ->after('id')
                ->constrained('establishments')
                ->cascadeOnDelete();
        });
    }

    /**
     * Supprimer la relation avec l'établissement.
     */
    public function down(): void
    {
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->dropForeign(['establishment_id']);
            $table->dropColumn('establishment_id');
        });
    }
};
