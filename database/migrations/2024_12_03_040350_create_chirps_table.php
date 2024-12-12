<?php

use Illuminate\Database\Migrations\Migration;
// นำเข้า Migration สำหรับการสร้างและจัดการโครงสร้างฐานข้อมูล

use Illuminate\Database\Schema\Blueprint;
// นำเข้า Blueprint สำหรับการกำหนดตารางในฐานข้อมูล

use Illuminate\Support\Facades\Schema;
// นำเข้า Schema เพื่อใช้ฟังก์ชันต่างๆ สำหรับจัดการฐานข้อมูล

return new class extends Migration
// การใช้คลาสอนุกรมที่ไม่ต้องตั้งชื่อ ซึ่งขยายจาก Migration เพื่อสร้างการอพยพฐานข้อมูล
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ฟังก์ชัน up ใช้ในการสร้างตารางหรือการเปลี่ยนแปลงฐานข้อมูล
        Schema::create('chirps', function (Blueprint $table) {
            // สร้างตาราง 'chirps' ด้วย Blueprint เพื่อกำหนดโครงสร้าง
            $table->id();
            // สร้างฟิลด์ id เป็น primary key (auto-increment)

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // สร้างฟิลด์ 'user_id' เป็น foreign key ที่เชื่อมโยงกับตาราง 'users'
            // - `constrained()`: เชื่อมโยงกับตาราง 'users' โดยอัตโนมัติ
            // - `cascadeOnDelete()`: หากผู้ใช้ถูกลบออก ข้อมูลใน 'chirps' ที่เกี่ยวข้องจะถูกลบตาม (cascade delete)

            $table->string('message');
            // สร้างฟิลด์ 'message' เป็นสตริงเพื่อเก็บข้อความของ Chirp

            $table->timestamps();
            // สร้างฟิลด์ 'created_at' และ 'updated_at' สำหรับบันทึกเวลาเมื่อมีการสร้างและแก้ไขข้อมูล
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ฟังก์ชัน down ใช้ในการย้อนการเปลี่ยนแปลงฐานข้อมูล (ในกรณีที่ต้องการ rollback)
        Schema::dropIfExists('chirps');
        // ลบตาราง 'chirps' หากมันมีอยู่
    }
};
