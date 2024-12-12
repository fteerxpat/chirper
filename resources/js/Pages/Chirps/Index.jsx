import React from 'react';
// นำเข้า React เพื่อใช้สำหรับสร้างคอมโพเนนต์ (components)

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
// นำเข้าเลย์เอาต์ที่แสดงเฉพาะเมื่อผู้ใช้งานเข้าสู่ระบบแล้ว

import Chirp from '@/Components/Chirp';
// นำเข้าคอมโพเนนต์ Chirp สำหรับแสดงข้อความของแต่ละ Chirp

import InputError from '@/Components/InputError';
// นำเข้าคอมโพเนนต์สำหรับแสดงข้อผิดพลาดที่เกี่ยวข้องกับอินพุต (input)

import PrimaryButton from '@/Components/PrimaryButton';
// นำเข้าปุ่มหลักที่ใช้ในฟอร์ม

import { useForm, Head } from '@inertiajs/react';
// นำเข้า `useForm` สำหรับจัดการข้อมูลฟอร์ม และ `Head` สำหรับจัดการส่วนหัวของเอกสาร (title, meta)

export default function Index({ auth, chirps }) {
// คอมโพเนนต์หลัก `Index` ที่รับข้อมูล `auth` (ข้อมูลผู้ใช้ที่เข้าสู่ระบบ) และ `chirps` (รายการข้อความทั้งหมด)

    const { data, setData, post, processing, reset, errors } = useForm({
      message: '',
    });
    // ใช้ useForm เพื่อจัดการข้อมูลฟอร์ม:
    // - `data` เก็บค่าข้อมูลในฟอร์ม
    // - `setData` ใช้เปลี่ยนแปลงค่าข้อมูลในฟอร์ม
    // - `post` ใช้ส่งคำขอ POST ไปยัง backend
    // - `processing` ระบุสถานะการประมวลผลคำขอ
    // - `reset` รีเซ็ตข้อมูลในฟอร์ม
    // - `errors` เก็บข้อผิดพลาดที่เกิดจากการตรวจสอบข้อมูล

    const submit = (e) => {
        e.preventDefault();
        // ป้องกันไม่ให้ฟอร์มรีเฟรชหน้าเมื่อกดปุ่มส่ง

        post(route('chirps.store'), { onSuccess: () => reset() })
        // ส่งคำขอ POST ไปยังเส้นทาง `chirps.store` พร้อมรีเซ็ตฟอร์มหากส่งสำเร็จ
    };

    return (
        <AuthenticatedLayout>
        {/* ใช้เลย์เอาต์ที่ต้องการการยืนยันตัวตนของผู้ใช้ */}

            <Head title="Chirps" />
            {/* กำหนดชื่อหน้าเป็น "Chirps" */}

            <div className="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            {/* กล่องคอนเทนต์ตรงกลางของหน้า */}

                <form onSubmit={submit}>
                {/* ฟอร์มสำหรับส่งข้อความใหม่ */}

                    <textarea
                        value={data.message}
                        placeholder="What's on your mind?"
                        className="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        onChange={e => setData('message', e.target.value)}
                    ></textarea>
                    {/* ช่องข้อความ (textarea) พร้อมแสดงและจัดการค่าข้อมูลในฟอร์ม */}

                    <InputError message={errors.message} className="mt-2" />
                    {/* แสดงข้อผิดพลาดใต้ช่องข้อความ หากมีข้อผิดพลาดเกี่ยวกับฟิลด์ message */}

                    <PrimaryButton className="mt-4" disabled={processing}>Chirp</PrimaryButton>
                    {/* ปุ่มส่งข้อความ หากกำลังประมวลผลจะไม่สามารถคลิกได้ */}
                </form>

                <div className="mt-6 bg-white shadow-sm rounded-lg divide-y">
                {/* ส่วนแสดงรายการ Chirps */}

                    {chirps.map(chirp =>
                        <Chirp key={chirp.id} chirp={chirp} />
                        // แสดงแต่ละ Chirp โดยใช้คอมโพเนนต์ Chirp และส่งข้อมูล chirp ไปเป็น props
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
