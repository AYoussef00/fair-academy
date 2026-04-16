<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * إنشاء حساب الأكاديمي للدخول إلى لوحة الأكاديمي.
     * البريد: teacher@gmail.com | كلمة المرور: password123
     */
    public function run(): void
    {
        $trainer = User::firstOrCreate(
            ['email' => 'teacher@gmail.com'],
            [
                'name' => 'الأكاديمي',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $trainer->wasRecentlyCreated) {
            $trainer->update(['password' => Hash::make('password123')]);
        }

        $trainer->syncRoles(['teacher']);
        $trainer->update(['role' => 'teacher']);
    }
}
