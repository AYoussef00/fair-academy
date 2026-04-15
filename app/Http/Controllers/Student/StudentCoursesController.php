<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentCoursesController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $enrollments = Enrollment::query()
            ->where('user_id', $user->id)
            ->with(['course.instructor', 'course.category', 'course.modules.lessons'])
            ->latest()
            ->get();

        $courses = $enrollments->map(function ($e) use ($user) {
            $course = $e->course;
            if (! $course) {
                return null;
            }

            $totalLessons = $course->modules?->sum(fn ($m) => $m->lessons?->count() ?? 0) ?? 0;
            $completedLessons = 0;
            if ($totalLessons > 0) {
                $lessonIds = $course->modules->pluck('lessons')->flatten()->pluck('id');
                $completedLessons = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('completed', true)
                    ->count();
            }

            $thumbnail = $course->thumbnail
                ? (str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/'.$course->thumbnail))
                : null;

            $firstLesson = $course->modules
                ->sortBy('order_number')
                ->flatMap(fn ($m) => $m->lessons->sortBy('order_number'))
                ->first();

            return [
                'id' => $course->id,
                'title' => $course->title,
                'thumbnail' => $thumbnail,
                'instructor' => $course->instructor->name ?? '',
                'category' => $course->category->name ?? '',
                'progress' => (float) $e->progress,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'enrolled_at' => $e->created_at?->format('Y-m-d'),
                'first_lesson_id' => $firstLesson?->id,
            ];
        })->filter()->values()->all();

        return Inertia::render('Student/Courses', [
            'courses' => $courses,
        ]);
    }
}
