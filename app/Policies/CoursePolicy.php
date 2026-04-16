<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('teacher') || $user->hasRole('admin');
    }

    public function view(User $user, Course $course): bool
    {
        return (int) $course->instructor_id === (int) $user->id || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('teacher') || $user->hasRole('admin');
    }

    public function update(User $user, Course $course): bool
    {
        return (int) $course->instructor_id === (int) $user->id || $user->hasRole('admin');
    }

    public function delete(User $user, Course $course): bool
    {
        return (int) $course->instructor_id === (int) $user->id || $user->hasRole('admin');
    }
}
