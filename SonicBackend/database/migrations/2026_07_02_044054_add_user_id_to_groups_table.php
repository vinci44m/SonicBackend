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
        Schema::table('groups', function (Blueprint $table) {
            // Prüfung: Nur hinzufügen, wenn die Spalte noch nicht existiert
            if (!Schema::hasColumn('groups', 'user_id')) {
                // Wir nutzen 'nullable()', damit existierende Gruppen keinen Fehler verursachen
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (Schema::hasColumn('groups', 'user_id')) {
                $table->dropForeign(['user_id']); // Erst die Verknüpfung lösen
                $table->dropColumn('user_id');    // Dann die Spalte löschen
            }
        });
    }
};