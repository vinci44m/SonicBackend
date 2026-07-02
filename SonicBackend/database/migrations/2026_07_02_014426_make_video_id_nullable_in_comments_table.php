<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('comments', function (Blueprint $table) {
        // Wir setzen die Spalte auf nullable, damit sie nicht mehr zwingend ausgefüllt sein muss
        $table->unsignedBigInteger('video_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('comments', function (Blueprint $table) {
        $table->unsignedBigInteger('video_id')->nullable(false)->change();
    });
}
};
