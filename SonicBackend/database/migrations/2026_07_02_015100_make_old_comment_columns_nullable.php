<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
{
    Schema::table('comments', function (Blueprint $table) {
        // Wir erlauben der Datenbank, diese Felder leer zu lassen
        $table->text('text')->nullable()->change();
        $table->unsignedBigInteger('video_id')->nullable()->change();
    });
}

public function down()
{
    // Hier müsste man im Fehlerfall den Status wieder zurücksetzen
    Schema::table('comments', function (Blueprint $table) {
        $table->text('text')->nullable(false)->change();
        $table->unsignedBigInteger('video_id')->nullable(false)->change();
    });
}
};
