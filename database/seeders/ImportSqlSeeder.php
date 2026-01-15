<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $path = database_path('seeders/dump/zipcode.sql');

            if (!File::exists($path)) {
                $this->command->error("ไม่พบไฟล์ SQL ที่ path: $path");
                return;
            }

            $sql = File::get($path);

            DB::unprepared($sql);

            $this->command->info('นำเข้า SQL สำเร็จ 🎉');
        } catch (\Exception $e) {
            // แสดง error ใน console
            $this->command->error('เกิดข้อผิดพลาดในการนำเข้า SQL: ' . $e->getMessage());

            // บันทึก error ลง log ด้วย (optional)
            Log::error('SQL Import Error: ' . $e->getMessage());
        }
    }
}
