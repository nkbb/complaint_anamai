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
            //

            $table->string('receive_user')->nullable()->after('send_files');
            $table->dateTime('receive_date')->nullable()->after('receive_user');
            $table->text('auth_fname')->nullable()->after('receive_date');
            $table->text('auth_lname')->nullable()->after('auth_fname');
            $table->text('auth_phone')->nullable()->after('auth_lname');
            $table->text('auth_email')->nullable()->after('auth_phone');

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
