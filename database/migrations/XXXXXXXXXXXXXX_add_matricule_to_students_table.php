<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter le matricule à la table students.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('matricule')->nullable()->unique()->after('id');
        });
    }

    /**
     * Supprimer le matricule.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->dropColumn('matricule');
        });
    }
};
