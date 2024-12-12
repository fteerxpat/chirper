<?php

use App\Http\Controllers\ChirpController; // เรียกใช้ ChirpController สำหรับจัดการคำขอ (request) ที่เกี่ยวข้องกับ Chirps
//เช่น การโพสต์, การแก้ไข หรือการลบข้อความ
use App\Http\Controllers\ProfileController; // เรียกใช้ ProfileController สำหรับจัดการคำขอที่เกี่ยวข้องกับโปรไฟล์ผู้ใช้
//เช่น การแก้ไข, การอัปเดต หรือการลบโปรไฟล์
use Illuminate\Foundation\Application; // เรียกใช้คลาส Application จาก Laravel Framework เพื่อเข้าถึงข้อมูลเกี่ยวกับแอปพลิเคชัน
//เช่น เวอร์ชันของ Laravel
use Illuminate\Support\Facades\Route; // เรียกใช้ Facade สำหรับกำหนดเส้นทาง (routes) ในแอปพลิเคชัน
use Inertia\Inertia; // เรียกใช้ Inertia.js เพื่อสร้าง SPA (Single Page Application) โดยใช้ Laravel เป็น backend และ Vue/React เป็น frontend

// หน้าแรกของเว็บไซต์ที่จะแสดง Welcome และข้อมูลเกี่ยวกับการเข้าสู่ระบบ, การลงทะเบียน, เวอร์ชัน Laravel และ PHP
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'), // ตรวจสอบว่ามี route สำหรับ login
        'canRegister' => Route::has('register'), // ตรวจสอบว่ามี route สำหรับ register
        'laravelVersion' => Application::VERSION, // แสดงเวอร์ชันของ Laravel
        'phpVersion' => PHP_VERSION, // แสดงเวอร์ชันของ PHP
    ]);
});

// เส้นทางเข้าสู่ Dashboard (ต้องเข้าสู่ระบบและยืนยันอีเมลก่อนถึงเข้าได้)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard'); // แสดงหน้า Dashboard
})->middleware(['auth', 'verified'])->name('dashboard'); // ใช้ middleware เพื่อควบคุมการเข้าถึง


// กลุ่มเส้นทางที่ต้องการการเข้าสู่ระบบ
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // แก้ไขโปรไฟล์
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // อัปเดตโปรไฟล์
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // ลบโปรไฟล์
});

// เส้นทางสำหรับ Chirps (โพสต์ข้อความ), จำกัดเฉพาะ index, store, update, destroy และต้องเข้าสู่ระบบพร้อมยืนยันอีเมล
Route::resource('chirps', ChirpController::class)
->only(['index', 'store', 'update', 'destroy']) // กำหนด action ที่อนุญาต
    ->middleware(['auth', 'verified']); // ใช้ middleware เพื่อควบคุมการเข้าถึง

// โหลดเส้นทางสำหรับการพิสูจน์ตัวตนเพิ่มเติม
require __DIR__.'/auth.php';
