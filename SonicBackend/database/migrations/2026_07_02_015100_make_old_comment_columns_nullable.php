<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                // Nur ändern, wenn die Spalte existiert
                if (Schema::hasColumn('comments', 'text')) {
                    $table->text('text')->nullable()->change();
                }
                if (Schema::hasColumn('comments', 'video_id')) {
                    $table->unsignedBigInteger('video_id')->nullable()->change();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                if (Schema::hasColumn('comments', 'text')) {
                    $table->text('text')->nullable(false)->change();
                }
                if (Schema::hasColumn('comments', 'video_id')) {
                    $table->unsignedBigInteger('video_id')->nullable(false)->change();
                }
            });
        }
    }
};