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
        Schema::create('complaint_comment', function (Blueprint $table) {
            $table->id();
            $table->text('ask_unit')->nullable();
            $table->text('comment_unit')->nullable();
            $table->text('user_ask')->nullable();
            $table->text('user_com')->nullable();
            $table->dateTime('date_ask')->nullable();
            $table->dateTime('date_com')->nullable();
            $table->smallInteger('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_comment');
    }
};
