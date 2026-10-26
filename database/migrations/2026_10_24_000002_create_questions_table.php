<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $t) {
            $t->id();
            $t->unsignedTinyInteger('difficulty')->default(1); // 1 easy, 2 medium, 3 hard
            $t->string('question', 255);
            $t->json('options');                               // all 4 options
            $t->string('answer', 120);                         // must equal one of the options
            $t->string('explanation', 255)->nullable();
            $t->timestamps();
            $t->index('difficulty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
