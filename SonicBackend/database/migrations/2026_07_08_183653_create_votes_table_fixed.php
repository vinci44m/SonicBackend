<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('votes')) {
            Schema::create('votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('post_id')->constrained();
                $table->string('type'); // 'up' oder 'down'
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};