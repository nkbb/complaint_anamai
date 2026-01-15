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
        Schema::create('question_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('question_id')->unsigned()->nullable();
            $table->bigInteger('vote_id')->unsigned()->nullable();
            $table->smallInteger('score');
            $table->timestamps();

            $table->foreign('vote_id')
            ->references('id')
            ->on('question_vote')
            ->onDelete('SET NULL');

            $table->foreign('question_id')
            ->references('id')
            ->on('question')
            ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_detail');
    }
};
