<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminUsersController extends Controller
{
    public function index(): Response
    {
        $users = User::query()
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?: ($user->roles->first()->name ?? 'student'),
                'created_at' => optional($user->created_at)->toDateString(),
            ])
            ->values();

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'roleOptions' => [
                ['value' => 'student', 'label' => 'طالب'],
                ['value' => 'teacher', 'label' => 'أكاديمي'],
                ['value' => 'academic_supervisor', 'label' => 'مشرف أكاديمي'],
                ['value' => 'accountant', 'label' => 'محاسب'],
                ['value' => 'admin_staff', 'label' => 'موظف تسجيل'],
                ['value' => 'admin', 'label' => 'إدارة'],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'role' => ['required', Rule::in(['admin', 'admin_staff', 'academic_supervisor', 'accountant', 'teacher', 'student'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $user->syncRoles([$validated['role']]);

        return back()->with('success', 'تم إضافة المستخدم بنجاح.');
    }
}
