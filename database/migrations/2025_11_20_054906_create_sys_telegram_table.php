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
        Schema::create('sys_telegram', function (Blueprint $table) {
            $table->id();
            $table->string('token')->nullable();
            $table->string('chat_id')->nullable();
            $table->smallInteger('type')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_telegram');
    }
};
