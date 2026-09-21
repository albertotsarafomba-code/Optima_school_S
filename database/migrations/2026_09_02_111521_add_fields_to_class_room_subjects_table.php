<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_room_subjects', function (Blueprint $table) {

            $table->foreignId('class_room_id')
                ->after('id')
                ->constrained('class_rooms')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->after('class_room_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->unsignedInteger('coefficient')
                ->default(1)
                ->after('subject_id');

            $table->unique([
                'class_room_id',
                'subject_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('class_room_subjects', function (Blueprint $table) {

            $table->dropUnique([
                'class_room_id',
                'subject_id'
            ]);

            $table->dropForeign(['class_room_id']);
            $table->dropForeign(['subject_id']);

            $table->dropColumn([
                'class_room_id',
                'subject_id',
                'coefficient',
            ]);
        });
    }
};
