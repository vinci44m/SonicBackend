<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Prüfung: Nur hinzufügen, wenn die Spalte noch nicht existiert
            if (!Schema::hasColumn('users', 'semester')) {
                $table->string('semester')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Gute Praxis: Wenn wir rollen, dann löschen wir die Spalte auch wieder
            if (Schema::hasColumn('users', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }
};