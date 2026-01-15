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
        Schema::table('complaints', function (Blueprint $table) {
             $table->text('answer_detail')->nullable()->after('auth_email');
             $table->string('answer_file')->nullable()->after('answer_detail');
             $table->string('answer_name')->nullable()->after('answer_file');
             $table->dateTime('answer_date')->nullable()->after('answer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            //
        });
    }
};
