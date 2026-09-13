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
        Schema::create('documents', function (Blueprint $table) {
             $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category', 30)->default('document');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_extension', 20)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_new')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'sort_order']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
