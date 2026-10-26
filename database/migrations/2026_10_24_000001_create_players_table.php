<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $t) {
            $t->id();
            $t->string('name', 16)->unique();
            $t->string('token', 40);                         // secret kept in the player's browser
            $t->unsignedInteger('best_score')->default(0);
            $t->unsignedTinyInteger('cleared')->default(0);  // highest world cleared (0..6)
            $t->boolean('banned')->default(false);
            $t->timestamps();
            $t->index('best_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
