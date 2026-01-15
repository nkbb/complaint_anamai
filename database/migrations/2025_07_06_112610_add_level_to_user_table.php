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
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('phone')->nullable()->after('name');
            $table->string('level')->after('phone');
            $table->bigInteger('unit_id')->unsigned()->nullable()->after('email');

            $table->foreign('unit_id')
            ->references('id')
            ->on('unit')
            ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
