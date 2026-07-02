<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Wir prüfen, ob 'posts' schon existiert, um den Absturz zu verhindern
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->string('title');
                $table->text('content');
                $table->json('tags')->nullable();
                $table->integer('votes')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Korrektur: Wir löschen jetzt 'posts', da wir es oben erstellen
        Schema::dropIfExists('posts');
    }
};