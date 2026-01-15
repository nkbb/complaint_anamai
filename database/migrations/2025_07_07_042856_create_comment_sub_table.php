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
        Schema::create('comment_sub', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('comment_type_id')->unsigned()->nullable();
            $table->smallInteger('num');
            $table->smallInteger('type');
            $table->timestamps();

            $table->foreign('comment_type_id')
            ->references('id')
            ->on('comment_type')
            ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_sub');
    }
};
