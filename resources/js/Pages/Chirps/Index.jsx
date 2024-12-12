import React from 'react'; // นำเข้า React สำหรับสร้างส่วนประกอบ (components)
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'; // ใช้เลย์เอาต์ที่แสดงผลเฉพาะเมื่อผู้ใช้เข้าสู่ระบบ
import Chirp from '@/Components/Chirp'; // นำเข้าส่วนประกอบสำหรับแสดง chirps แต่ละอัน
import InputError from '@/Components/InputError'; // นำเข้าส่วนประกอบสำหรับแสดงข้อผิดพลาดของการป้อนข้อมูล
import PrimaryButton from '@/Components/PrimaryButton'; // นำเข้าปุ่มหลักที่ออกแบบไว้
import { useForm, Head } from '@inertiajs/react'; // นำเข้า hooks `useForm` สำหรับจัดการฟอร์ม และ `Head` สำหรับตั้งค่าหัวข้อของหน้า

export default function Index({ auth, chirps }) { // ฟังก์ชันหลักที่ใช้สำหรับเรนเดอร์หน้า chirps รับ `auth` และ `chirps` เป็น props
    const { data, setData, post, processing, reset, errors } = useForm({ // ใช้ `useForm` สำหรับจัดการข้อมูลฟอร์ม
      message: '', // กำหนดค่าเริ่มต้นของฟิลด์ `message`
    });

    const submit = (e) => { // ฟังก์ชันสำหรับจัดการเมื่อฟอร์มถูกส่ง
        e.preventDefault(); // ป้องกันการรีเฟรชหน้า
        post(route('chirps.store'), { onSuccess: () => reset() }); // ส่งข้อมูลไปยัง route `chirps.store` และรีเซ็ตฟอร์มเมื่อสำเร็จ
    };

    return (
        <AuthenticatedLayout> {/* ใช้เลย์เอาต์สำหรับผู้ใช้ที่เข้าสู่ระบบ */}
            <Head title="Chirps" /> {/* ตั้งชื่อหัวข้อของหน้าเป็น "Chirps" */}

            <div className="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8"> {/* กำหนดความกว้างและระยะห่างของคอนเทนเนอร์ */}
                <form onSubmit={submit}> {/* ฟอร์มสำหรับสร้าง chirp */}
                    <textarea
                        value={data.message} // กำหนดค่าข้อความให้เชื่อมโยงกับ `data.message`
                        placeholder="What's on your mind?" // ข้อความที่แสดงเมื่อยังไม่ได้ป้อนข้อมูล
                        className="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" // สไตล์ของ textarea
                        onChange={e => setData('message', e.target.value)} // อัปเดตค่า `message` เมื่อมีการเปลี่ยนแปลง
                    ></textarea>
                    <InputError message={errors.message} className="mt-2" /> {/* แสดงข้อความข้อผิดพลาดถ้ามี */}
                    <PrimaryButton className="mt-4" disabled={processing}>Chirp</PrimaryButton> {/* ปุ่มสำหรับส่งฟอร์ม */}
                </form>

                <div className="mt-6 bg-white shadow-sm rounded-lg divide-y"> {/* ส่วนสำหรับแสดงรายการ chirps */}
                    {chirps.map(chirp => // ลูปรายการ chirps
                        <Chirp key={chirp.id} chirp={chirp} /> // แสดง chirp แต่ละตัวโดยส่งข้อมูล chirp ไปยัง component
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
