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
        Schema::create('th_subdistrict', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('zip_code');
            $table->smallInteger('province_id')->nullable();
            $table->smallInteger('district_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('th_subdistrict');
    }
};
