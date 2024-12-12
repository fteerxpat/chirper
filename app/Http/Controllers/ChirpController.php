<?php

namespace App\Http\Controllers; // กำหนด namespace ของไฟล์ให้อยู่ใน App\Http\Controllers

use Illuminate\Support\Facades\Gate; // ใช้สำหรับจัดการการอนุญาต (authorization) ผ่าน Gate
use App\Models\Chirp; // นำเข้ารุ่น (Model) Chirp สำหรับใช้งานในคอนโทรลเลอร์นี้
use Illuminate\Http\RedirectResponse; // ใช้สำหรับการตอบกลับแบบเปลี่ยนเส้นทาง (redirect)
use Illuminate\Http\Request; // ใช้สำหรับจัดการคำขอ HTTP
use Inertia\Inertia; // ใช้ Inertia.js ในการเรนเดอร์หน้า
use Inertia\Response; // ใช้สำหรับส่งตอบกลับ Inertia Response

class ChirpController extends Controller // คลาสคอนโทรลเลอร์สำหรับจัดการ "chirps"
{
    /**
     * แสดงรายการของ resource ทั้งหมด
     */
    public function index(): Response
    {
        return Inertia::render('Chirps/Index', [ // เรนเดอร์หน้า "Chirps/Index"
            'chirps' => Chirp::with('user:id,name')->latest()->get(), // ดึงข้อมูล chirps พร้อมข้อมูลผู้ใช้ (user) และเรียงลำดับล่าสุดก่อน
        ]);
    }

    /**
     * แสดงฟอร์มสำหรับสร้าง resource ใหม่ (ยังไม่ใช้งานในตัวอย่างนี้)
     */
    public function create()
    {
        //
    }

    /**
     * บันทึก resource ใหม่ในระบบ
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([ // ตรวจสอบความถูกต้องของข้อมูลจากคำขอ
            'message' => 'required|string|max:255', // message ต้องมีค่า, เป็น string และยาวไม่เกิน 255 ตัวอักษร
        ]);

        $request->user()->chirps()->create($validated); // สร้าง chirp ใหม่และเชื่อมโยงกับผู้ใช้ปัจจุบัน

        return redirect(route('chirps.index')); // เปลี่ยนเส้นทางกลับไปยังหน้ารายการ chirps
    }

    /**
     * แสดง resource ที่ระบุ (ยังไม่ใช้งานในตัวอย่างนี้)
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไข resource ที่ระบุ (ยังไม่ใช้งานในตัวอย่างนี้)
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * อัปเดต resource ที่ระบุในระบบ
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp); // ตรวจสอบสิทธิ์ผู้ใช้ว่ามีสิทธิ์แก้ไข chirp นี้หรือไม่

        $validated = $request->validate([ // ตรวจสอบความถูกต้องของข้อมูลจากคำขอ
            'message' => 'required|string|max:255', // message ต้องมีค่า, เป็น string และยาวไม่เกิน 255 ตัวอักษร
        ]);

        $chirp->update($validated); // อัปเดตข้อมูล chirp

        return redirect(route('chirps.index')); // เปลี่ยนเส้นทางกลับไปยังหน้ารายการ chirps
    }

    /**
     * ลบ resource ที่ระบุออกจากระบบ
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp); // ตรวจสอบสิทธิ์ผู้ใช้ว่ามีสิทธิ์ลบ chirp นี้หรือไม่

        $chirp->delete(); // ลบ chirp ออกจากฐานข้อมูล

        return redirect(route('chirps.index')); // เปลี่ยนเส้นทางกลับไปยังหน้ารายการ chirps
    }
}
