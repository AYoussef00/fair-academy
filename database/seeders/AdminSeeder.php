<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * إنشاء حساب الأدمن للدخول إلى لوحة الإدارة و /cyber
     * البريد: admin@gmail.com | كلمة المرور: password123
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'الأدمن',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $admin->wasRecentlyCreated) {
            $admin->update(['password' => Hash::make('password123')]);
        }

        $admin->syncRoles(['admin']);
        $admin->update(['role' => 'admin']);
    }
}
