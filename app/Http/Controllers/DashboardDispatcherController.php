<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardDispatcherController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('admin') || $user->hasRole('admin_staff')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('teacher')) {
            return redirect()->route('instructor.dashboard');
        }

        if (! $user->roles()->exists()) {
            $user->update(['role' => 'student']);
            $user->assignRole('student');
        }

        return redirect()->route('student.dashboard');
    }
}
