<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('school_types')->updateOrInsert(
            ['name' => 'Préscolaire'],
            [
                'name' => 'Préscolaire',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('school_types')
            ->where('name', 'Préscolaire')
            ->delete();
    }
};
