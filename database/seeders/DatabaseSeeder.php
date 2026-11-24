<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
         * ملاحظة:
         * هذا Seeder تجريبي لقاعدة بيانات Yallajo حسب مخطط قاعدة البيانات المرسل.
         * شغّله على قاعدة بيانات نظيفة باستخدام migrate:fresh --seed.
         */

        // 1) المستخدمون: Admin / Adventurer / Activity_Organizer
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'مدير يلاجو',
                'email' => 'admin@yallajo.test',
                'email_verified_at' => $now,
                'phone_number' => '0790000001',
                'role' => 'Admin',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'ليان الخطيب',
                'email' => 'layan@example.com',
                'email_verified_at' => $now,
                'phone_number' => '0791111111',
                'role' => 'Adventurer',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'عمر العجارمة',
                'email' => 'omar@example.com',
                'email_verified_at' => $now,
                'phone_number' => '0782222222',
                'role' => 'Adventurer',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'سارة الحسن',
                'email' => 'sara@example.com',
                'email_verified_at' => $now,
                'phone_number' => '0773333333',
                'role' => 'Adventurer',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'أحمد الرواشدة',
                'email' => 'ahmad.organizer@example.com',
                'email_verified_at' => $now,
                'phone_number' => '0794444444',
                'role' => 'Activity_Organizer',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'نور أبو رمان',
                'email' => 'noor.organizer@example.com',
                'email_verified_at' => $now,
                'phone_number' => '0785555555',
                'role' => 'Activity_Organizer',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2) الملفات الشخصية للمغامرين
        DB::table('adventurers')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'experience_level' => 'Beginner',
                'interests' => json_encode(['هايكنج', 'التخييم', 'التصوير'], JSON_UNESCAPED_UNICODE),
                'location' => 'عمّان',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'experience_level' => 'Advanced',
                'interests' => json_encode(['تسلّق', 'دراجات', 'غوص'], JSON_UNESCAPED_UNICODE),
                'location' => 'إربد',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'user_id' => 4,
                'experience_level' => 'Intermediate',
                'interests' => json_encode(['هايكنج', 'رحلات', 'تخييم'], JSON_UNESCAPED_UNICODE),
                'location' => 'السلط',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 3) منظمو الأنشطة
        DB::table('activity_organizers')->insert([
            [
                'id' => 1,
                'user_id' => 5,
                'license_number' => 'JOR-ORG-1001',
                'organizer_type' => 'Adventure_group',
                'approval_status' => 'Approved',
                'social_handle_url' => 'https://instagram.com/jordan.trails.example',
                'approved_by' => 1,
                'approved_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'user_id' => 6,
                'license_number' => 'JOR-ORG-1002',
                'organizer_type' => 'Tourism_office',
                'approval_status' => 'Approved',
                'social_handle_url' => 'https://instagram.com/jordan.outdoors.example',
                'approved_by' => 1,
                'approved_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 4) المغامرات والأنشطة المتاحة في الأردن
        DB::table('adventures')->insert([
            [
                'id' => 1,
                'title' => 'مغامرة وادي الموجب',
                'description' => 'رحلة مائية بين الصخور والشلالات في محمية الموجب، مع مرشدين وتعليمات سلامة قبل بدء المسار.',
                'activity_organizer_id' => 1,
                'difficulty_level' => 'Medium',
                'capacity' => 12,
                'category' => 'Hiking',
                'location' => 'محمية الموجب، مادبا',
                'duration_hours' => 4.00,
                'price' => 25.00,
                'requirements' => 'ملابس مناسبة للماء، حذاء مانع للانزلاق، لياقة بدنية مناسبة، والالتزام بتعليمات المرشد.',
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'title' => 'استكشاف مسارات غابات عجلون',
                'description' => 'مسار مشي وسط أشجار البلوط والصنوبر، مع استراحة للتصوير والتعرّف على الطبيعة المحلية.',
                'activity_organizer_id' => 1,
                'difficulty_level' => 'Easy',
                'capacity' => 18,
                'category' => 'Hiking',
                'location' => 'محمية غابات عجلون، عجلون',
                'duration_hours' => 3.00,
                'price' => 12.50,
                'requirements' => 'حذاء مريح للمشي، ماء، ووجبة خفيفة. النشاط مناسب للمبتدئين.',
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'title' => 'ليلة تخييم تحت النجوم في وادي رم',
                'description' => 'تجربة تخييم في الصحراء تتضمن جولة قصيرة، عشاءً محلياً، ومشاهدة النجوم عند صفاء السماء.',
                'activity_organizer_id' => 2,
                'difficulty_level' => 'Easy',
                'capacity' => 20,
                'category' => 'Camping',
                'location' => 'وادي رم، العقبة',
                'duration_hours' => 18.00,
                'price' => 45.00,
                'requirements' => 'ملابس دافئة للمساء، مستلزمات شخصية، وإبلاغ المنظم عن أي احتياجات غذائية مسبقاً.',
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'title' => 'جولة دراجات في البحر الميت',
                'description' => 'جولة بالدراجات على مسار محدد بإطلالات على البحر الميت، مع توقفات للاستراحة والتصوير.',
                'activity_organizer_id' => 2,
                'difficulty_level' => 'Medium',
                'capacity' => 10,
                'category' => 'Cyciling',
                'location' => 'منطقة البحر الميت، الأردن',
                'duration_hours' => 2.50,
                'price' => 20.00,
                'requirements' => 'القدرة على قيادة الدراجة، حذاء رياضي، وملابس مريحة. الخوذة متوفرة من المنظم.',
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 5) المرشدون السياحيون
        DB::table('guides')->insert([
    [
        'id' => 1,
        'activity_organizer_id' => 1,
        'bio' => 'مرشد محلي يهتم بمسارات المشي والطبيعة في شمال ووسط الأردن.',
        'experience_years' => 5,
        'specialization' => json_encode(['الهايكنج', 'المسارات الطبيعية']),
        'cetification' => 'شهادة إسعافات أولية وإرشاد مسارات',
        'status' => 'Available',
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'id' => 2,
        'activity_organizer_id' => 2,
        'bio' => 'مرشدة سياحية متخصصة في الرحلات الصحراوية والتخييم والتعريف بالثقافة المحلية.',
        'experience_years' => 4,
        'specialization' => json_encode(['السياحة الصحراوية', 'التخييم']),
        'cetification' => 'شهادة إرشاد سياحي',
        'status' => 'Available',
        'created_at' => $now,
        'updated_at' => $now,
    ],
]);

        // 6) توفر المرشدين
        DB::table('guides_availabilities')->insert([
            ['id' => 1, 'guide_id' => 1, 'status' => 'Available', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'guide_id' => 2, 'status' => 'Available', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 7) أيام وتفاصيل البرامج
        DB::table('adventure_days')->insert([
            [
                'id' => 1,
                'adventure_id' => 1,
                'day_number' => 1,
                'title' => 'الوصول والانطلاق في مسار الموجب',
                'description' => 'التجمع عند نقطة الالتقاء، فحص المعدات، شرح تعليمات السلامة، ثم بدء المسار المائي.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'adventure_id' => 2,
                'day_number' => 1,
                'title' => 'مسار الغابة والاستراحة',
                'description' => 'التجمع عند مدخل المحمية، المشي في المسار المحدد، ثم استراحة قصيرة للتصوير.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'adventure_id' => 3,
                'day_number' => 1,
                'title' => 'الوصول والتخييم',
                'description' => 'استقبال المشاركين، توزيع أماكن الإقامة، جولة صحراوية، ثم العشاء ومشاهدة النجوم.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'adventure_id' => 4,
                'day_number' => 1,
                'title' => 'جولة الدراجات بمحاذاة البحر الميت',
                'description' => 'توزيع الدراجات والخوذ، شرح مسار الجولة، ثم الانطلاق مع توقفات للتصوير والاستراحة.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 8) مواعيد تنفيذ المغامرات
        DB::table('adventure_schedules')->insert([
            [
                'id' => 1,
                'adventure_id' => 1,
                'guide_id' => 1,
                'starts_at' => now()->addDays(7)->setTime(8, 0),
                'ends_at' => now()->addDays(7)->setTime(12, 0),
                'capacity' => 12,
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'adventure_id' => 2,
                'guide_id' => 1,
                'starts_at' => now()->addDays(10)->setTime(9, 0),
                'ends_at' => now()->addDays(10)->setTime(12, 0),
                'capacity' => 18,
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'adventure_id' => 3,
                'guide_id' => 2,
                'starts_at' => now()->addDays(14)->setTime(15, 0),
                'ends_at' => now()->addDays(15)->setTime(9, 0),
                'capacity' => 20,
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'adventure_id' => 4,
                'guide_id' => 2,
                'starts_at' => now()->addDays(16)->setTime(7, 30),
                'ends_at' => now()->addDays(16)->setTime(10, 0),
                'capacity' => 10,
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 9) حجوزات المغامرات
        DB::table('bookings')->insert([
            [
                'id' => 1,
                'adventure_id' => 1,
                'adventurer_id' => 1,
                'participants_count' => 2,
                'unit_price' => 25.00,
                'commission_rate' => 10.00,
                'total_amount' => 50.00,
                'status' => 'Accepted',
                'capacity' => 12,
                'cancelled_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'adventure_id' => 2,
                'adventurer_id' => 2,
                'participants_count' => 1,
                'unit_price' => 12.50,
                'commission_rate' => 10.00,
                'total_amount' => 12.50,
                'status' => 'Pending',
                'capacity' => 18,
                'cancelled_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'adventure_id' => 3,
                'adventurer_id' => 3,
                'participants_count' => 2,
                'unit_price' => 45.00,
                'commission_rate' => 10.00,
                'total_amount' => 90.00,
                'status' => 'Accepted',
                'capacity' => 20,
                'cancelled_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 10) دفعات الحجوزات
        DB::table('payments')->insert([
            [
                'id' => 1,
                'booking_id' => 1,
                'total_amount' => 50.00,
                'method' => 'Visa',
                'status' => 'Confirmed',
                'paid_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'booking_id' => 2,
                'total_amount' => 12.50,
                'method' => 'Cash',
                'status' => 'Pending',
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'booking_id' => 3,
                'total_amount' => 90.00,
                'method' => 'Visa',
                'status' => 'Confirmed',
                'paid_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 11) تقييمات المغامرات
        DB::table('reviews')->insert([
            [
                'id' => 1,
                'adventurer_id' => 1,
                'adventure_id' => 1,
                'booking_id' => 1,
                'rating' => 5,
                'comment' => 'تجربة رائعة والتنظيم كان ممتازاً. المرشد متعاون والمسار ممتع!',
                'is_reported' => 0,
                'report_status' => 'None',
                'report_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'adventurer_id' => 3,
                'adventure_id' => 3,
                'booking_id' => 3,
                'rating' => 4,
                'comment' => 'أجواء وادي رم جميلة جداً، والتخييم كان تجربة مميزة.',
                'is_reported' => 0,
                'report_status' => 'None',
                'report_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 12) صور المغامرات (روابط صور خارجية)
        DB::table('images')->insert([
            ['id' => 1, 'adventure_id' => 1, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Wadi_Mujib-06-2010-gje.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'adventure_id' => 1, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Wadi_Mujib-18-2010-gje.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'adventure_id' => 2, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Ajloun_Forest_Reserve_in_Jordan_kz05.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'adventure_id' => 2, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Ajloun_Forest_Reserve_in_Jordan_kz04.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'adventure_id' => 3, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Jordan_-_Wadi_Rum_-_Landscape.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'adventure_id' => 3, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Wadi_Rum,_Tree_in_the_Middle_of_the_Desert.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'adventure_id' => 4, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Dead_Sea,_Jordan_01.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'adventure_id' => 4, 'image_path' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Dead_Sea,_Jordan_02.jpg?width=1280', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 13) المجتمعات المحلية
        DB::table('communities')->insert([
            [
                'id' => 1,
                'name' => 'مغامرو الأردن',
                'slug' => 'jordan-adventurers',
                'description' => 'مجتمع لمحبي الهايكنج والتخييم والأنشطة الخارجية في مختلف مناطق الأردن.',
                'region' => 'الأردن',
                'activity_style' => 'متنوع',
                'cover_image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Wadi_Rum,_Jordan,_Infinite_spaces.jpg?width=1280',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'دروب الشمال',
                'slug' => 'north-trails',
                'description' => 'مساحة لمشاركة تجارب ومسارات الطبيعة والرحلات في عجلون وإربد والسلط.',
                'region' => 'شمال الأردن',
                'activity_style' => 'هايكنج وطبيعة',
                'cover_image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Ajloun_forests_JO_2.jpg?width=1280',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 14) عضوية المستخدمين في المجتمعات
        DB::table('community_memberships')->insert([
            ['id' => 1, 'community_id' => 1, 'user_id' => 2, 'role' => 'Member', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'community_id' => 1, 'user_id' => 3, 'role' => 'Member', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'community_id' => 1, 'user_id' => 5, 'role' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'community_id' => 2, 'user_id' => 4, 'role' => 'Member', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'community_id' => 2, 'user_id' => 6, 'role' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 15) رسائل المجتمعات
        DB::table('community_messages')->insert([
            [
                'id' => 1,
                'community_id' => 1,
                'user_id' => 2,
                'body' => 'مرحبا جميعاً! مين جرّب مسار وادي الموجب؟ شو بتنصحونا نجهّز معنا؟',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'community_id' => 1,
                'user_id' => 3,
                'body' => 'المسار ممتع، أهم إشي حذاء مناسب للماء والالتزام بتعليمات المرشد.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'community_id' => 2,
                'user_id' => 4,
                'body' => 'غابات عجلون بتجنّن بالخريف. في حدا مهتم نرتّب طلعة جماعية؟',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

    }
}
