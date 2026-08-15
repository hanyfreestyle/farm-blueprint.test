<?php

namespace Database\Seeders;

use App\Enums\Setting\EnumsSocialPlatform;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'hany.freestyle4u@gmail.com'],
            [
                'name' => 'Hany Darwish',
                'password' => Hash::make('hany.freestyle4u@gmail.com'),
                'phone' => '+201221563252',
                'phone_country' => 'EG',
                'social' => [
                    [
                        'platform' => EnumsSocialPlatform::Facebook->value,
                        'url' => 'https://www.facebook.com/hany.darwish',
                    ],
                    [
                        'platform' => EnumsSocialPlatform::LinkedIn->value,
                        'url' => 'https://www.linkedin.com/in/hany-darwish',
                    ],
                ],
                'slug' => [
                    'ar' => 'هاني-درويش',
                    'en' => 'hany-darwish',
                ],
                'author_name' => [
                    'ar' => 'هاني درويش',
                    'en' => 'Hany Darwish',
                ],
                'job_title' => [
                    'ar' => 'مدير النظام',
                    'en' => 'System Administrator',
                ],
                'des' => [
                    'ar' => 'نبذة تعريفية عن المستخدم الأساسي داخل لوحة التحكم.',
                    'en' => 'Profile summary for the primary administrator inside the control panel.',
                ],
                'short_des' => [
                    'ar' => 'المستخدم الأساسي للنظام.',
                    'en' => 'Primary system user.',
                ],
                'g_h1' => [
                    'ar' => 'هاني درويش',
                    'en' => 'Hany Darwish',
                ],
                'g_title' => [
                    'ar' => 'هاني درويش | المستخدم الأساسي',
                    'en' => 'Hany Darwish | Primary User',
                ],
                'g_des' => [
                    'ar' => 'الملف التعريفي للمستخدم الأساسي في نظام Laravel Core.',
                    'en' => 'Profile page for the primary user in the Laravel Core system.',
                ],
            ]
        );

        $user->syncRoles(['super_admin']);
    }
}
