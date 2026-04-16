<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminStudentsController extends Controller
{
    public function index(): Response
    {
        $students = User::query()
            ->where('role', 'student')
            ->orWhereHas('roles', fn ($query) => $query->where('name', 'student'))
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => $user->status,
                'created_at' => optional($user->created_at)->toDateString(),
            ])
            ->values();

        return Inertia::render('Admin/Students', [
            'students' => $students,
        ]);
    }
}
