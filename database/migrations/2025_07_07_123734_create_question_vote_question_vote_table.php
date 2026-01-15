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
        Schema::create('question_vote', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('gender');
            $table->smallInteger('age');
            $table->smallInteger('qualification');
            $table->smallInteger('work');
            $table->string('work_dis');
            $table->string('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_vote');
    }
};
