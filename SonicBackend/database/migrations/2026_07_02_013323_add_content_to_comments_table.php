<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Nur hinzufügen, wenn die Spalte noch nicht existiert
            if (!Schema::hasColumn('comments', 'content')) {
                $table->text('content')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Nur löschen, wenn die Spalte existiert
            if (Schema::hasColumn('comments', 'content')) {
                $table->dropColumn('content');
            }
        });
    }
};