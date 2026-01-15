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
            $table->text('fname')->nullable()->change();
            $table->text('lname')->nullable()->change();
            $table->text('work')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->text('tel')->nullable()->change();
            $table->text('phone')->nullable()->change();
            $table->text('email')->nullable()->change();
            $table->text('name')->nullable()->change();

            $table->text('idcard')->nullable()->after('lname');
            $table->string('idcard_sub')->nullable()->after('idcard');

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
