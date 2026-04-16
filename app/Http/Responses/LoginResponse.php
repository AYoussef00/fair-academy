<?php

namespace App\Http\Responses;

use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $user = $request->user();
        $user->load('roles');

        if ($user->hasRole('admin') || $user->hasRole('admin_staff')) {
            return Inertia::location(route('admin.dashboard'));
        }

        if ($user->hasRole('teacher')) {
            return Inertia::location(route('instructor.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }
}
