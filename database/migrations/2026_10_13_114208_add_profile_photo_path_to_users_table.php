<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'profile_photo_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo_path')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Ne pas supprimer profile_photo_path :
        // cette colonne est également gérée par la migration précédente.
    }
};
