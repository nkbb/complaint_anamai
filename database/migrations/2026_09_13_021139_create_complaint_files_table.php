<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('complaint_id')
                ->constrained('complaints')
                ->cascadeOnDelete();

            // image, pdf, video
            $table->string('file_type', 20);

            // ชื่อไฟล์ที่ผู้ใช้อัปโหลด
            $table->string('original_name');

            // ชื่อไฟล์ที่ระบบสร้าง
            $table->string('file_name');

            // เช่น complaints/15/filename.jpg
            $table->string('file_path');

            $table->string('mime_type', 150)->nullable();
            $table->string('extension', 20)->nullable();

            // ขนาดเป็น byte
            $table->unsignedBigInteger('file_size')->default(0);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['complaint_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_files');
    }
};