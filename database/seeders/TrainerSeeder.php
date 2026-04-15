<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * إنشاء حساب المدرب للدخول إلى لوحة المدرب.
     * البريد: trainer@gmail.com | كلمة المرور: password123
     */
    public function run(): void
    {
        $trainer = User::firstOrCreate(
            ['email' => 'trainer@gmail.com'],
            [
                'name' => 'المدرب',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $trainer->wasRecentlyCreated) {
            $trainer->update(['password' => Hash::make('password123')]);
        }

        $trainer->syncRoles(['trainer']);
        $trainer->update(['role' => 'trainer']);
    }
}
