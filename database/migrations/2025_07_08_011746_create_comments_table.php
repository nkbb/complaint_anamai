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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->bigInteger('sub_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('comment');
            $table->string('ip');
            $table->timestamps();

            $table->foreign('type_id')
            ->references('id')
            ->on('comment_type')
            ->onDelete('SET NULL');

            $table->foreign('sub_id')
            ->references('id')
            ->on('comment_sub')
            ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
