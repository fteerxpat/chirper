<?php

namespace App\Models;
// กำหนด namespace ของคลาสนี้ให้อยู่ใน App\Models

use Illuminate\Database\Eloquent\Relations\HasMany;
// นำเข้า HasMany เพื่อใช้งานความสัมพันธ์แบบ One-to-Many

use Illuminate\Database\Eloquent\Factories\HasFactory;
// นำเข้า HasFactory สำหรับการใช้งาน Factory ในการสร้างข้อมูลตัวอย่าง

use Illuminate\Foundation\Auth\User as Authenticatable;
// นำเข้า Authenticatable ซึ่งเป็นคลาสที่ทำให้ User สามารถใช้งานระบบ authentication ได้

use Illuminate\Notifications\Notifiable;
// นำเข้า Notifiable เพื่อให้ User สามารถส่งการแจ้งเตือนได้ (notifications)

class User extends Authenticatable
// คลาส User ขยายจาก Authenticatable ซึ่งทำให้คลาสนี้เป็นโมเดลสำหรับจัดการผู้ใช้งานในระบบ

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    // ใช้ Traits HasFactory และ Notifiable
    // - HasFactory: สำหรับการสร้างข้อมูลจำลองในฐานข้อมูล
    // - Notifiable: เพื่อให้โมเดล User สามารถส่งการแจ้งเตือนได้

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    // กำหนดฟิลด์ที่อนุญาตให้กรอกข้อมูลได้โดยตรง (mass assignment)

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // กำหนดฟิลด์ที่ต้องการซ่อนเมื่อโมเดลถูกแปลงเป็น array หรือ JSON
    // - password: เพื่อความปลอดภัย
    // - remember_token: token ใช้สำหรับการจำสถานะการล็อกอิน

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // กำหนดการแปลงค่าของฟิลด์ในฐานข้อมูล:
    // - email_verified_at: แปลงเป็น datetime
    // - password: เก็บข้อมูลแบบ hashed เพื่อความปลอดภัย

    public function chirps(): HasMany
    {
        return $this->hasMany(Chirp::class);
    }
    // สร้างความสัมพันธ์แบบ One-to-Many ระหว่าง User และ Chirp
    // - User หนึ่งคนสามารถมี Chirps หลายรายการ
    // - ใช้ฟังก์ชันนี้เพื่อดึงข้อมูล Chirps ทั้งหมดของ User
}
