<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;

class AdminCoursesController extends Controller
{
    public function index(): Response
    {
        $courses = Course::query()
            ->with(['program:id,title', 'instructor:id,name'])
            ->withCount('enrollments')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Course $course) => [
                'id' => $course->id,
                'title' => $course->title,
                'program' => $course->program?->title,
                'instructor' => $course->instructor?->name,
                'price' => (float) $course->price,
                'duration' => $course->duration,
                'status' => $course->status,
                'students_count' => (int) $course->enrollments_count,
                'created_at' => optional($course->created_at)->toDateString(),
            ])
            ->values();

        return Inertia::render('Admin/Courses', [
            'courses' => $courses,
        ]);
    }
}
