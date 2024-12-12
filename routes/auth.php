<?php

// นำเข้า Controller ที่เกี่ยวข้องกับการจัดการการเข้าสู่ระบบ และการจัดการผู้ใช้ในระบบ Auth ของ Laravel
use App\Http\Controllers\Auth\AuthenticatedSessionController; // สำหรับการจัดการเซสชันของผู้ใช้ที่ล็อกอิน
use App\Http\Controllers\Auth\ConfirmablePasswordController; // สำหรับการยืนยันรหัสผ่านก่อนดำเนินการต่างๆ เช่น การเปลี่ยนรหัสผ่าน
use App\Http\Controllers\Auth\EmailVerificationNotificationController; // สำหรับการส่งอีเมลยืนยันการสมัครสมาชิก
use App\Http\Controllers\Auth\EmailVerificationPromptController; // สำหรับการแสดงหน้าการแจ้งเตือนให้ยืนยันอีเมล
use App\Http\Controllers\Auth\NewPasswordController; // สำหรับการตั้งรหัสผ่านใหม่หลังจากการรีเซ็ตรหัสผ่าน
use App\Http\Controllers\Auth\PasswordController; // สำหรับการจัดการการเปลี่ยนรหัสผ่านของผู้ใช้
use App\Http\Controllers\Auth\PasswordResetLinkController; // สำหรับการขอส่งลิงก์การรีเซ็ตรหัสผ่านไปยังอีเมล
use App\Http\Controllers\Auth\RegisteredUserController; // สำหรับการจัดการการลงทะเบียนผู้ใช้ใหม่
use App\Http\Controllers\Auth\VerifyEmailController; // สำหรับการตรวจสอบและยืนยันอีเมลของผู้ใช้

// ใช้ Route facade ของ Laravel เพื่อกำหนดเส้นทางต่างๆ ในแอปพลิเคชัน
use Illuminate\Support\Facades\Route; // ใช้ฟังก์ชัน Route เพื่อกำหนดเส้นทางของแอปพลิเคชัน


// กลุ่มเส้นทางที่ใช้ middleware 'guest' ซึ่งจะถูกเข้าถึงได้เมื่อผู้ใช้ยังไม่ได้ล็อกอิน
Route::middleware('guest')->group(function () {
    // เส้นทางสำหรับการลงทะเบียนผู้ใช้ใหม่
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register'); // แสดงฟอร์มลงทะเบียน

    Route::post('register', [RegisteredUserController::class, 'store']);
    // เส้นทางสำหรับส่งข้อมูลการลงทะเบียนไปยังเซิร์ฟเวอร์

    // เส้นทางสำหรับการเข้าสู่ระบบ
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login'); // แสดงฟอร์มเข้าสู่ระบบ

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    // เส้นทางสำหรับส่งข้อมูลเข้าสู่ระบบไปยังเซิร์ฟเวอร์

    // เส้นทางสำหรับการขอรีเซ็ตรหัสผ่าน
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request'); // แสดงฟอร์มขอรีเซ็ตรหัสผ่าน

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email'); // ส่งอีเมลการขอรีเซ็ตรหัสผ่าน

    // เส้นทางสำหรับการรีเซ็ตรหัสผ่านด้วย token ที่ได้รับ
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset'); // แสดงฟอร์มให้ผู้ใช้ตั้งรหัสผ่านใหม่

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store'); // ส่งข้อมูลรหัสผ่านใหม่ไปยังเซิร์ฟเวอร์
});

// กลุ่มเส้นทางที่ใช้ middleware 'auth' ซึ่งจะเข้าถึงได้เมื่อผู้ใช้ล็อกอินแล้ว
Route::middleware('auth')->group(function () {
    // เส้นทางสำหรับแสดงหน้าการยืนยันอีเมล
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice'); // แสดงคำแนะนำให้ยืนยันอีเมล

    // เส้นทางสำหรับยืนยันอีเมลเมื่อคลิกลิงก์ยืนยันในอีเมล
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1']) // ใช้ middleware signed และ throttle
        ->name('verification.verify'); // ตรวจสอบลายเซ็นของลิงก์ยืนยัน

    // เส้นทางสำหรับส่งการแจ้งเตือนการยืนยันอีเมลใหม่
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1') // จำกัดการร้องขอซ้ำ
        ->name('verification.send');

    // เส้นทางสำหรับแสดงหน้าขอให้ยืนยันรหัสผ่านก่อนเปลี่ยนรหัส
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    // เส้นทางสำหรับยืนยันรหัสผ่านก่อนดำเนินการเปลี่ยนแปลง
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // เส้นทางสำหรับการเปลี่ยนรหัสผ่าน
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // เส้นทางสำหรับออกจากระบบ
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
