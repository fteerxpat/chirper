<?php

namespace App\Models; // กำหนด namespace ของโมเดลให้อยู่ใน App\Models

use Illuminate\Database\Eloquent\Relations\BelongsTo; // นำเข้า BelongsTo สำหรับกำหนดความสัมพันธ์แบบหลายต่อหนึ่ง
use Illuminate\Database\Eloquent\Model; // นำเข้า Model ซึ่งเป็นคลาสพื้นฐานสำหรับโมเดลใน Laravel

class Chirp extends Model // คลาส Chirp สำหรับจัดการข้อมูลของ chirps
{
    protected $fillable = [ // กำหนดฟิลด์ที่อนุญาตให้เพิ่มหรือแก้ไขโดย mass assignment
        'message', // ฟิลด์ข้อความของ chirp
    ];

    /**
     * กำหนดความสัมพันธ์แบบหลายต่อหนึ่ง (Many-to-One) กับ User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo // ฟังก์ชันสำหรับกำหนดความสัมพันธ์ว่าหนึ่ง chirp เป็นของผู้ใช้หนึ่งคน
    {
        return $this->belongsTo(User::class); // กำหนดความสัมพันธ์ระหว่าง Chirp และ User
    }
}
