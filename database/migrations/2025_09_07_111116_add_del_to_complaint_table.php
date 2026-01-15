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
            $table->string('del_user')->nullable()->after('ip');
            $table->dateTime('del_date')->nullable()->after('del_user');
            $table->text('del_comm')->nullable()->after('del_date');
            $table->string('send_user')->nullable()->after('del_comm');
            $table->dateTime('send_date')->nullable()->after('send_user');
            $table->string('send_unit')->nullable()->after('send_date');
            $table->text('send_comm')->nullable()->after('send_unit');
            $table->text('send_files')->nullable()->after('send_comm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint', function (Blueprint $table) {
            //
        });
    }
};
