<?php

use App\Http\Controllers\ChirpController; // ใช้ ChirpController ในการจัดการเส้นทางสำหรับ "chirps"
use App\Http\Controllers\ProfileController; // ใช้ ProfileController ในการจัดการเส้นทางที่เกี่ยวกับโปรไฟล์
use Illuminate\Foundation\Application; // ใช้สำหรับเรียกข้อมูลเวอร์ชันของ Laravel
use Illuminate\Support\Facades\Route; // ใช้สำหรับสร้างและจัดการเส้นทาง (routes)
use Inertia\Inertia; // ใช้ Inertia.js ในการเรนเดอร์หน้าแบบ SPA

// เส้นทางสำหรับหน้าแรกของเว็บไซต์
Route::get('/', function () {
    return Inertia::render('Welcome', [ // เรนเดอร์หน้า "Welcome" โดยส่งข้อมูลเพิ่มเติมไปให้
        'canLogin' => Route::has('login'), // ตรวจสอบว่ามีเส้นทางสำหรับ login หรือไม่
        'canRegister' => Route::has('register'), // ตรวจสอบว่ามีเส้นทางสำหรับ register หรือไม่
        'laravelVersion' => Application::VERSION, // ส่งข้อมูลเวอร์ชันของ Laravel
        'phpVersion' => PHP_VERSION, // ส่งข้อมูลเวอร์ชันของ PHP
    ]);
});

// เส้นทางสำหรับหน้าแดชบอร์ด
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard'); // เรนเดอร์หน้า "Dashboard"
})->middleware(['auth', 'verified']) // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบและยืนยันตัวตนแล้ว
  ->name('dashboard'); // ตั้งชื่อให้กับเส้นทางนี้ว่า "dashboard"

// กลุ่มเส้นทางที่ต้องการการยืนยันตัวตน (auth middleware)
Route::middleware('auth')->group(function () {
    // เส้นทางสำหรับแก้ไขโปรไฟล์
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // เส้นทางสำหรับอัปเดตข้อมูลโปรไฟล์
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // เส้นทางสำหรับลบโปรไฟล์
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// เส้นทางสำหรับ "chirps" ที่ต้องการการยืนยันตัวตนและการตรวจสอบ
Route::resource('chirps', ChirpController::class) // ใช้ Resource Controller สำหรับจัดการเส้นทาง
    ->only(['index', 'store', 'update', 'destroy']) // กำหนดเฉพาะบางฟังก์ชันที่ใช้ (index, store, update, destroy)
    ->middleware(['auth', 'verified']); // ใช้ middleware เพื่อยืนยันตัวตนและการตรวจสอบ

// โหลดไฟล์เส้นทางเพิ่มเติมที่เกี่ยวกับการยืนยันตัวตน
require __DIR__.'/auth.php';
