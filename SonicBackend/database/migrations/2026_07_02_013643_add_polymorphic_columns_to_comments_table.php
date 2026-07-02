<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Prüfung für commentable_id
            if (!Schema::hasColumn('comments', 'commentable_id')) {
                $table->unsignedBigInteger('commentable_id')->nullable();
            }
            // Prüfung für commentable_type
            if (!Schema::hasColumn('comments', 'commentable_type')) {
                $table->string('commentable_type')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Nur löschen, wenn sie auch wirklich da sind
            if (Schema::hasColumn('comments', 'commentable_id')) {
                $table->dropColumn('commentable_id');
            }
            if (Schema::hasColumn('comments', 'commentable_type')) {
                $table->dropColumn('commentable_type');
            }
        });
    }
};