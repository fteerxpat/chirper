//import React from 'react';
import React, { useState } from 'react';
// นำเข้า React และ useState จาก React เพื่อจัดการกับ state ของ component

import Dropdown from '@/Components/Dropdown';
// นำเข้า Dropdown component สำหรับการแสดงเมนูที่สามารถเลือกได้ เช่น "Edit" และ "Delete"

import InputError from '@/Components/InputError';
// นำเข้า InputError component สำหรับแสดงข้อความแสดงข้อผิดพลาด

import PrimaryButton from '@/Components/PrimaryButton';
// นำเข้า PrimaryButton component สำหรับปุ่มที่ใช้ในฟอร์ม

import dayjs from 'dayjs';
// นำเข้า dayjs สำหรับจัดการวันที่และเวลา

import relativeTime from 'dayjs/plugin/relativeTime';
// นำเข้า plugin 'relativeTime' ของ dayjs เพื่อแสดงเวลาในรูปแบบเช่น "2 minutes ago"

import { useForm, usePage } from '@inertiajs/react';
// นำเข้า useForm และ usePage จาก @inertiajs/react สำหรับการจัดการฟอร์มและเข้าถึงข้อมูลในหน้าเว็บ

dayjs.extend(relativeTime);
// ใช้ plugin 'relativeTime' ใน dayjs เพื่อให้สามารถแสดงเวลาที่สัมพันธ์กับปัจจุบัน เช่น "2 hours ago"

export default function Chirp({ chirp }) {
// สร้าง component Chirp ที่รับ prop `chirp` ซึ่งเป็นข้อมูลข้อความ (Chirp) ที่จะแสดง

    const { auth } = usePage().props;
    // เข้าถึงข้อมูลผู้ใช้จาก `usePage()` เพื่อให้สามารถตรวจสอบว่าเป็นผู้ใช้คนเดียวกันที่สร้างข้อความนี้หรือไม่

    const [editing, setEditing] = useState(false);
    // สร้าง state `editing` เพื่อเก็บสถานะว่าอยู่ในโหมดแก้ไขหรือไม่

    const { data, setData, patch, clearErrors, reset, errors } = useForm({
        message: chirp.message,
    });
    // ใช้ useForm สำหรับการจัดการฟอร์ม โดยใช้ข้อความของ chirp เป็นค่าเริ่มต้น
    // `patch`: ใช้สำหรับการส่งคำขอ PATCH (แก้ไข) ไปที่เซิร์ฟเวอร์
    // `clearErrors` และ `reset`: ใช้สำหรับล้างข้อผิดพลาดและรีเซ็ตข้อมูลในฟอร์ม
    // `errors`: เก็บข้อผิดพลาดที่เกิดขึ้นในฟอร์ม

    const submit = (e) => {
        e.preventDefault();
        patch(route('chirps.update', chirp.id), { onSuccess: () => setEditing(false) });
    };
    // ฟังก์ชัน submit จะถูกเรียกเมื่อผู้ใช้ส่งฟอร์ม
    // ใช้ `patch` ส่งคำขอไปที่ route 'chirps.update' เพื่ออัพเดตข้อความที่เลือก โดยส่ง `chirp.id` ไปเป็นพารามิเตอร์
    // เมื่อสำเร็จจะตั้งค่า `editing` เป็น false เพื่อปิดโหมดแก้ไข

    return (
        <div className="p-6 flex space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                <path strokeLinecap="round" strokeLinejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <div className="flex-1">
                <div className="flex justify-between items-center">
                    <div>
                        <span className="text-gray-800">{chirp.user.name}</span>
                        <small className="ml-2 text-sm text-gray-600">{dayjs(chirp.created_at).fromNow()}</small>
                        { chirp.created_at !== chirp.updated_at && <small className="text-sm text-gray-600"> &middot; edited</small>}
                    </div>
                    {chirp.user.id === auth.user.id &&
                        <Dropdown>
                            <Dropdown.Trigger>
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </Dropdown.Trigger>
                            <Dropdown.Content>
                                <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" onClick={() => setEditing(true)}>
                                    Edit
                                </button>
                                <Dropdown.Link as="button" href={route('chirps.destroy', chirp.id)} method="delete">
                                    Delete
                                </Dropdown.Link>
                            </Dropdown.Content>
                        </Dropdown>
                    }
                </div>

                {editing
                    ? <form onSubmit={submit}>
                        <textarea value={data.message} onChange={e => setData('message', e.target.value)} className="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                        <InputError message={errors.message} className="mt-2" />
                        <div className="space-x-2">
                            <PrimaryButton className="mt-4">Save</PrimaryButton>
                            <button className="mt-4" onClick={() => { setEditing(false); reset(); clearErrors(); }}>Cancel</button>
                        </div>
                    </form>
                    : <p className="mt-4 text-lg text-gray-900">{chirp.message}</p>
                }
            </div>
        </div>
    )
}
