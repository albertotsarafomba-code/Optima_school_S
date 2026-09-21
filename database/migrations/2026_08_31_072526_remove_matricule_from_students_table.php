<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Supprimer le matricule de la table students.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->dropColumn('matricule');
        });
    }

    /**
     * Restaurer le matricule si nécessaire.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('matricule')->unique()->after('id');
        });
    }
};