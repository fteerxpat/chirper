<?php

namespace App\Http\Controllers;
// กำหนด namespace ของคลาสให้อยู่ใน App\Http\Controllers เพื่อจัดระเบียบโครงสร้างและช่วยให้สามารถเรียกใช้คลาสได้อย่างถูกต้อง
use Illuminate\Support\Facades\Gate;
// เรียกใช้ Gate สำหรับกำหนดนโยบายสิทธิ์การเข้าถึง (authorization policies) ในแอปพลิเคชัน
use App\Models\Chirp;
// นำเข้าโมเดล Chirp เพื่อใช้งาน เช่น ดึงข้อมูล, เพิ่ม, ลบ หรือแก้ไขข้อมูลในฐานข้อมูลที่เกี่ยวข้องกับ Chirps
use Illuminate\Http\RedirectResponse;
// ใช้สำหรับกำหนดผลลัพธ์ของคำขอ (request) ที่ต้องการเปลี่ยนเส้นทาง (redirect) เช่น การบันทึกข้อมูลแล้วเปลี่ยนเส้นทางไปหน้าอื่น
use Illuminate\Http\Request;
// ใช้สำหรับจัดการคำขอ HTTP เช่น ดึงข้อมูลที่ผู้ใช้ส่งมาผ่านฟอร์มหรือ URL
use Inertia\Inertia;
// ใช้ Inertia.js ในการเรนเดอร์หน้าเว็บในรูปแบบ Single Page Application (SPA) โดยทำงานร่วมกับ Laravel และ Vue/React
use Inertia\Response;
// ใช้สำหรับส่งข้อมูลกลับไปยัง frontend ผ่าน Inertia.js โดยกำหนดรูปแบบผลลัพธ์ที่ใช้ Inertia

class ChirpController extends Controller
// คลาส ChirpController ขยายจาก Controller สำหรับจัดการกับ resource "Chirps" ในแอปพลิเคชัน
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Chirps/Index', [
            'chirps' => Chirp::with('user:id,name')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $request->user()->chirps()->create($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }
}
