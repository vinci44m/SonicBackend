<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Wir prüfen zuerst, ob die Tabelle und Spalte überhaupt existieren
        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'video_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedBigInteger('video_id')->nullable()->change();
            });
        }
    }

    public function down()
    {
        // Zum Zurückrollen prüfen wir ebenfalls
        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'video_id')) {
            Schema::table('comments', function (Blueprint $table) {
                // Hinweis: "nullable(false)" ist gleichbedeutend mit "change()"
                $table->unsignedBigInteger('video_id')->nullable(false)->change();
            });
        }
    }
};