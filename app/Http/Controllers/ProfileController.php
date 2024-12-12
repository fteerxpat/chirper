<?php

namespace App\Http\Controllers;

// นำเข้า ProfileUpdateRequest ที่ใช้สำหรับการตรวจสอบข้อมูลที่ผู้ใช้ส่งมาในแบบฟอร์ม
use App\Http\Requests\ProfileUpdateRequest;

// นำเข้าคลาสที่เกี่ยวข้องกับการทำงานของ Auth และการจัดการ session
use Illuminate\Contracts\Auth\MustVerifyEmail; // ใช้เพื่อการตรวจสอบอีเมลของผู้ใช้
use Illuminate\Http\RedirectResponse; // ใช้สำหรับการส่งคำตอบเป็นการเปลี่ยนเส้นทาง
use Illuminate\Http\Request; // ใช้ในการรับข้อมูลจากคำขอ HTTP
use Illuminate\Support\Facades\Auth; // ใช้ในการจัดการการยืนยันตัวตนและการล็อกอินของผู้ใช้
use Illuminate\Support\Facades\Redirect; // ใช้ในการสร้างการเปลี่ยนเส้นทาง
use Inertia\Inertia; // ใช้ในการส่งข้อมูลไปยัง Inertia.js ในส่วนของ frontend
use Inertia\Response; // ใช้ในการตอบกลับคำขอจาก Inertia

class ProfileController extends Controller
{
    /**
     * แสดงฟอร์มโปรไฟล์ของผู้ใช้
     */
    public function edit(Request $request): Response
    {
        // ใช้ Inertia เพื่อแสดงฟอร์มโปรไฟล์ (Profile/Edit) และส่งข้อมูลไปยัง frontend
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail, // ตรวจสอบว่าผู้ใช้ต้องยืนยันอีเมลหรือไม่
            'status' => session('status'), // ส่งสถานะที่เก็บใน session (ถ้ามี) ไปยัง frontend
        ]);
    }

    /**
     * อัปเดตข้อมูลโปรไฟล์ของผู้ใช้
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // ใช้ข้อมูลที่ได้รับจาก ProfileUpdateRequest และกรอกข้อมูลลงในผู้ใช้ที่ล็อกอิน
        $request->user()->fill($request->validated());

        // ถ้าผู้ใช้เปลี่ยนอีเมล เราตั้งค่าให้ต้องยืนยันอีเมลใหม่
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // บันทึกการเปลี่ยนแปลงข้อมูลของผู้ใช้
        $request->user()->save();

        // เปลี่ยนเส้นทางกลับไปยังหน้าฟอร์มโปรไฟล์
        return Redirect::route('profile.edit');
    }

    /**
     * ลบบัญชีผู้ใช้
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ตรวจสอบว่าได้กรอกรหัสผ่านเพื่อยืนยันการลบบัญชีหรือไม่
        $request->validate([
            'password' => ['required', 'current_password'], // ต้องใช้รหัสผ่านปัจจุบันของผู้ใช้
        ]);

        // รับข้อมูลของผู้ใช้ที่ล็อกอิน
        $user = $request->user();

        // ล็อกเอาท์ผู้ใช้
        Auth::logout();

        // ลบบัญชีผู้ใช้จากฐานข้อมูล
        $user->delete();

        // ทำการยกเลิก session ของผู้ใช้
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // เปลี่ยนเส้นทางไปยังหน้าหลักของเว็บไซต์
        return Redirect::to('/');
    }
}
