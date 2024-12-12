<?php

namespace App\Models;
// กำหนด namespace ของคลาสนี้ให้อยู่ใน App\Models

use Illuminate\Database\Eloquent\Relations\BelongsTo;
// นำเข้า BelongsTo เพื่อใช้งานความสัมพันธ์แบบ Many-to-One (ในที่นี้คือ Chirp มีความสัมพันธ์แบบ Many กับ User)

use Illuminate\Database\Eloquent\Model;
// นำเข้า Model เพื่อให้สามารถใช้งานคุณสมบัติของ Eloquent ORM

class Chirp extends Model
// คลาส Chirp ขยายจาก Eloquent Model ซึ่งหมายความว่า Chirp เป็นโมเดลในฐานข้อมูล
{
    protected $fillable = [
        'message',
    ];
    // กำหนดฟิลด์ที่อนุญาตให้กรอกข้อมูลได้โดยตรง (mass assignment)
    // ในที่นี้คือ 'message' ฟิลด์ที่บันทึกข้อความของ Chirp

    public function user(): BelongsTo
    {
        // ฟังก์ชันที่ใช้กำหนดความสัมพันธ์แบบ Many-to-One
        // - Chirp แต่ละรายการจะเชื่อมโยงกับผู้ใช้ (User) หนึ่งคน

        return $this->belongsTo(User::class);
        // ฟังก์ชัน `belongsTo` ระบุว่า Chirp นี้เป็นของผู้ใช้ (User) หนึ่งคน
        // โดยการเชื่อมโยงกับโมเดล User ผ่านฟิลด์ที่มีชื่อว่า `user_id` (อาจมีในฐานข้อมูล)
    }
}
