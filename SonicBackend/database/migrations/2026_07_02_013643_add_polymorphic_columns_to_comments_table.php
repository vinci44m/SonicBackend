<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Fügt die ID des zugehörigen Modells hinzu (z.B. Post-ID)
            $table->unsignedBigInteger('commentable_id')->nullable();
            // Fügt den Typ hinzu (z.B. "App\Models\Post")
            $table->string('commentable_type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['commentable_id', 'commentable_type']);
        });
    }
};
