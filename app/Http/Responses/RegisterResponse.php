<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['user' => $request->user()]);
        }

        $user = $request->user();

        if ($user->hasRole('admin') || $user->hasRole('admin_staff')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->hasRole('teacher')) {
            return redirect()->intended(route('instructor.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }
}
