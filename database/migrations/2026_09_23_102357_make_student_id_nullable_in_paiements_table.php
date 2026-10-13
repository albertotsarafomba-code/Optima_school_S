<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permettre les paiements d'enseignants :
     * student_id peut être NULL lorsque teacher_id est renseigné.
     */
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Rétablir student_id comme obligatoire.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')
                ->nullable(false)
                ->change();
        });
    }
};
