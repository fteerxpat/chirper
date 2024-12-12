<?php

namespace App\Models; // กำหนด namespace ของโมเดลให้อยู่ใน App\Models

// use Illuminate\Contracts\Auth\MustVerifyEmail; // คอมเมนต์ออกเพราะยังไม่ได้ใช้การตรวจสอบอีเมล
use Illuminate\Database\Eloquent\Relations\HasMany; // นำเข้า HasMany สำหรับกำหนดความสัมพันธ์แบบหนึ่งต่อหลาย
use Illuminate\Database\Eloquent\Factories\HasFactory; // นำเข้า HasFactory สำหรับสร้างข้อมูลแบบจำลอง (factory)
use Illuminate\Foundation\Auth\User as Authenticatable; // ใช้คลาส Authenticatable เพื่อกำหนดผู้ใช้ที่สามารถเข้าสู่ระบบ
use Illuminate\Notifications\Notifiable; // ใช้ Notifiable สำหรับส่งการแจ้งเตือน

class User extends Authenticatable // คลาสโมเดลสำหรับผู้ใช้
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; // ใช้ trait HasFactory และ Notifiable ในโมเดลนี้

    /**
     * รายการของแอตทริบิวต์ที่สามารถกำหนดค่าแบบกลุ่มได้
     *
     * @var array<int, string>
     */
    protected $fillable = [ // กำหนดฟิลด์ที่อนุญาตให้เพิ่มหรือแก้ไขโดย mass assignment
        'name', // ชื่อผู้ใช้
        'email', // อีเมลผู้ใช้
        'password', // รหัสผ่าน
    ];

    /**
     * รายการของแอตทริบิวต์ที่ต้องซ่อนเมื่อทำการ serialize
     *
     * @var array<int, string>
     */
    protected $hidden = [ // กำหนดฟิลด์ที่ต้องซ่อน เช่นเมื่อแปลงเป็น JSON
        'password', // ซ่อนรหัสผ่าน
        'remember_token', // ซ่อนโทเค็นสำหรับการจดจำการเข้าสู่ระบบ
    ];

    /**
     * กำหนดแอตทริบิวต์ที่ต้องถูกแปลงข้อมูล (cast)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // แปลงค่า email_verified_at เป็นชนิด datetime
            'password' => 'hashed', // แปลงค่า password ให้เป็น hashed
        ];
    }

    /**
     * กำหนดความสัมพันธ์แบบหนึ่งต่อหลาย (One-to-Many) กับ Chirp
     *
     * @return HasMany
     */
    public function chirps(): HasMany // ฟังก์ชันนี้แสดงความสัมพันธ์ว่าผู้ใช้หนึ่งคนสามารถมีหลาย chirps ได้
    {
        return $this->hasMany(Chirp::class); // กำหนดความสัมพันธ์ระหว่าง User และ Chirp
    }
}
