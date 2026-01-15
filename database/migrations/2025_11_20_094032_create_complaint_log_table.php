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
        Schema::create('complaint_log', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time')->nullable();
            $table->bigInteger('complaint_id')->unsigned()->nullable();
            $table->string('user_id')->nullable();
            $table->bigInteger('comment_id')->unsigned()->nullable();
            $table->smallInteger('type')->nullable();
            $table->timestamps();

            $table->foreign('complaint_id')
            ->references('id')
            ->on('complaints')
            ->onDelete('SET NULL');

             $table->foreign('comment_id')
            ->references('id')
            ->on('complaint_comment')
            ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_log');
    }
};
