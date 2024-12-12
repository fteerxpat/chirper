// import React from 'react'; // (ไม่ได้ใช้งาน)
import React, { useState } from 'react'; // นำเข้า React และ useState สำหรับจัดการ state ในฟังก์ชันคอมโพเนนต์
import Dropdown from '@/Components/Dropdown'; // นำเข้า Dropdown คอมโพเนนต์สำหรับเมนูแบบเลื่อน
import InputError from '@/Components/InputError'; // นำเข้า InputError สำหรับแสดงข้อผิดพลาดในฟอร์ม
import PrimaryButton from '@/Components/PrimaryButton'; // นำเข้า PrimaryButton สำหรับปุ่มหลักใน UI
import dayjs from 'dayjs'; // นำเข้า dayjs สำหรับจัดการวันที่และเวลา
import relativeTime from 'dayjs/plugin/relativeTime'; // นำเข้า plugin relativeTime เพื่อใช้แสดงเวลาแบบสัมพันธ์
import { useForm, usePage } from '@inertiajs/react'; // นำเข้า useForm และ usePage จาก Inertia.js สำหรับจัดการฟอร์มและข้อมูลหน้า

dayjs.extend(relativeTime); // เปิดใช้งาน plugin relativeTime ใน dayjs

export default function Chirp({ chirp }) { // สร้างคอมโพเนนต์ Chirp และรับ prop 'chirp' สำหรับแสดงข้อมูลแต่ละ chirp
    const { auth } = usePage().props; // ดึงข้อมูล auth จาก props ของ Inertia.js

    const [editing, setEditing] = useState(false); // สร้าง state 'editing' เพื่อตรวจสอบว่ากำลังแก้ไขข้อความหรือไม่

    const { data, setData, patch, clearErrors, reset, errors } = useForm({ // ใช้ useForm เพื่อจัดการข้อมูลฟอร์ม
        message: chirp.message, // กำหนดค่าเริ่มต้นของฟิลด์ message
    });

    const submit = (e) => { // ฟังก์ชันเมื่อฟอร์มถูกส่ง
        e.preventDefault(); // ป้องกันการรีเฟรชหน้า
        patch(route('chirps.update', chirp.id), { onSuccess: () => setEditing(false) }); // ส่งคำขออัปเดตข้อความและปิดโหมดแก้ไขเมื่อสำเร็จ
    };

    return (
        <div className="p-6 flex space-x-2"> {/* กล่องข้อความที่จัดตำแหน่งภายใน */}
            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"> {/* ไอคอนสำหรับแสดง */}
                <path strokeLinecap="round" strokeLinejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <div className="flex-1"> {/* จัดตำแหน่งเนื้อหาให้ขยายเต็มพื้นที่ */}
                <div className="flex justify-between items-center"> {/* แถบแสดงชื่อผู้ใช้และปุ่มจัดการ */}
                    <div>
                        <span className="text-gray-800">{chirp.user.name}</span> {/* แสดงชื่อผู้ใช้งาน */}
                        <small className="ml-2 text-sm text-gray-600">{dayjs(chirp.created_at).fromNow()}</small> {/* แสดงเวลาในรูปแบบสัมพันธ์ */}
                        { chirp.created_at !== chirp.updated_at && <small className="text-sm text-gray-600"> &middot; edited</small>} {/* หากมีการแก้ไขข้อความ แสดงคำว่า edited */}
                    </div>
                    {chirp.user.id === auth.user.id && // แสดงปุ่มจัดการเมื่อ chirp เป็นของผู้ใช้ที่เข้าสู่ระบบ
                        <Dropdown>
                            <Dropdown.Trigger>
                                <button> {/* ปุ่มสำหรับเปิดเมนู Dropdown */}
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </Dropdown.Trigger>
                            <Dropdown.Content> {/* เมนูใน Dropdown */}
                                <button className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" onClick={() => setEditing(true)}> {/* ปุ่มแก้ไข */}
                                    Edit
                                </button>
                                <Dropdown.Link as="button" href={route('chirps.destroy', chirp.id)} method="delete"> {/* ปุ่มลบข้อความ */}
                                    Delete
                                </Dropdown.Link>
                            </Dropdown.Content>
                        </Dropdown>
                    }
                </div>

                {editing // ตรวจสอบว่ากำลังอยู่ในโหมดแก้ไขหรือไม่
                    ? <form onSubmit={submit}> {/* ฟอร์มสำหรับแก้ไขข้อความ */}
                        <textarea value={data.message} onChange={e => setData('message', e.target.value)} className="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea> {/* กล่องข้อความ */}
                        <InputError message={errors.message} className="mt-2" /> {/* แสดงข้อผิดพลาดในฟอร์ม */}
                        <div className="space-x-2">
                            <PrimaryButton className="mt-4">Save</PrimaryButton> {/* ปุ่มบันทึก */}
                            <button className="mt-4" onClick={() => { setEditing(false); reset(); clearErrors(); }}>Cancel</button> {/* ปุ่มยกเลิก */}
                        </div>
                    </form>
                    : <p className="mt-4 text-lg text-gray-900">{chirp.message}</p> // แสดงข้อความในโหมดอ่าน
                }
            </div>
        </div>
    )
}
