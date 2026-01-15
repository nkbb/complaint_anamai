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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('concealed');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->smallInteger('gender')->nullable();
            $table->string('work')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->bigInteger('subdistrict_id')->unsigned()->nullable();
            $table->string('zipcode')->nullable();
            $table->string('tel')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('unit_id')->unsigned()->nullable();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->bigInteger('sub_id')->unsigned()->nullable();
            $table->bigInteger('person_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('type')->default(1);
            $table->bigInteger('method_id')->unsigned()->nullable()->default(10);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();

            // $table->foreign('province_id')
            // ->references('id')
            // ->on('th_province')
            // ->onDelete('SET NULL');

            // $table->foreign('district_id')
            // ->references('id')
            // ->on('th_district')
            // ->onDelete('SET NULL');

            // $table->foreign('subdistrict_id')
            // ->references('id')
            // ->on('th_subdistrict')
            // ->onDelete('SET NULL');

            $table->foreign('unit_id')
            ->references('id')
            ->on('unit')
            ->onDelete('SET NULL');

            $table->foreign('method_id')
            ->references('id')
            ->on('complaint_method')
            ->onDelete('SET NULL');

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('SET NULL');

            $table->foreign('person_id')
            ->references('id')
            ->on('complaint_person')
            ->onDelete('SET NULL');

            $table->foreign('type_id')
            ->references('id')
            ->on('complaint_type')
            ->onDelete('SET NULL');

            $table->foreign('sub_id')
            ->references('id')
            ->on('complaint_sub')
            ->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
