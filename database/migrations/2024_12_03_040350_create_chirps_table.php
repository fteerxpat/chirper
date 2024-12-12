<?php

use Illuminate\Database\Migrations\Migration; // นำเข้า Migration สำหรับสร้างหรือแก้ไขโครงสร้างตารางฐานข้อมูล
use Illuminate\Database\Schema\Blueprint; // นำเข้า Blueprint สำหรับกำหนดโครงสร้างตาราง
use Illuminate\Support\Facades\Schema; // นำเข้า Schema สำหรับจัดการตารางในฐานข้อมูล

return new class extends Migration // สร้างคลาสที่ขยายจาก Migration
{
    /**
     * ทำการสร้างโครงสร้างตารางเมื่อรันคำสั่ง `migrate`
     */
    public function up(): void // เมธอดนี้ทำงานเมื่อรัน `php artisan migrate`
    {
        Schema::create('chirps', function (Blueprint $table) { // สร้างตารางชื่อ 'chirps'
            $table->id(); // สร้างคอลัมน์ id เป็น primary key แบบ auto increment
            $table->foreignId('user_id') // สร้างคอลัมน์ foreign key ชื่อ user_id
                ->constrained() // เชื่อมโยงกับตาราง users โดยอัตโนมัติ
                ->cascadeOnDelete(); // ลบข้อมูลใน chirps เมื่อผู้ใช้ที่เกี่ยวข้องถูกลบ
            $table->string('message'); // สร้างคอลัมน์ข้อความชื่อ message ที่มีชนิดข้อมูลเป็น string
            $table->timestamps(); // สร้างคอลัมน์ created_at และ updated_at อัตโนมัติ
        });
    }

    /**
     * ย้อนกลับการเปลี่ยนแปลงเมื่อรันคำสั่ง `migrate:rollback`
     */
    public function down(): void // เมธอดนี้ทำงานเมื่อรัน `php artisan migrate:rollback`
    {
        Schema::dropIfExists('chirps'); // ลบตาราง chirps ออกจากฐานข้อมูล
    }
};
