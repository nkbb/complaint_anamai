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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key_title');
            $table->string('address');
            $table->string('email');
            $table->smallInteger('dead_date_send');
            $table->smallInteger('dead_date_receive');
            $table->smallInteger('dead_date_answer');
            $table->smallInteger('dead_date_finish');
            $table->text('conditions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
