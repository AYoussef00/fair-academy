<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class StudentDashboardController extends Controller
{
    private function mapEnrollmentsToCourses(Collection $items): array
    {
        $userId = $items->first()?->user_id;

        return $items->map(function ($e) use ($userId) {
            $course = $e->course;
            $totalLessons = $course->modules?->sum(fn ($m) => $m->lessons?->count() ?? 0) ?? 0;
            $completedLessons = 0;
            if ($userId && $totalLessons > 0) {
                $lessonIds = $course->modules->pluck('lessons')->flatten()->pluck('id');
                $completedLessons = \App\Models\LessonProgress::query()
                    ->where('user_id', $userId)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('completed', true)
                    ->count();
            }
            $thumbnail = $course->thumbnail
                ? (str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/'.$course->thumbnail))
                : null;

            return [
                'id' => $course->id,
                'title' => $course->title,
                'thumbnail' => $thumbnail,
                'instructor' => $course->instructor->name ?? '',
                'progress' => (float) $e->progress,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
            ];
        })->values()->all();
    }

    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $enrollments = Enrollment::query()
            ->where('user_id', $user->id)
            ->with(['course.program', 'course.instructor', 'course.modules.lessons'])
            ->get();

        $programsCount = $enrollments->pluck('course.program_id')->filter()->unique()->count();
        $coursesCount = $enrollments->count();

        $groupedByProgram = $enrollments
            ->groupBy(fn ($e) => $e->course->program_id ?? 'standalone');

        $enrolledProgramsWithCourses = [];
        foreach ($groupedByProgram as $pid => $items) {
            $first = $items->first();
            $course = $first->course;
            if (! $course) {
                continue;
            }
            $program = $course->program;
            if ($program) {
                $enrolledProgramsWithCourses[] = [
                    'program' => [
                        'id' => $program->id,
                        'title' => $program->title,
                        'thumbnail' => $program->thumbnail,
                        'slug' => $program->slug,
                    ],
                    'courses' => $this->mapEnrollmentsToCourses($items),
                ];
            } else {
                $enrolledProgramsWithCourses[] = [
                    'program' => [
                        'id' => 0,
                        'title' => 'دوراتي',
                        'thumbnail' => null,
                        'slug' => 'my-courses',
                    ],
                    'courses' => $this->mapEnrollmentsToCourses($items),
                ];
            }
        }

        if ($coursesCount === 0) {
            $programsCount = 2;
            $coursesCount = 2;
            $enrolledProgramsWithCourses = $this->demoEnrolledData();
        }

        return Inertia::render('Student/Dashboard', [
            'programsCount' => $programsCount,
            'coursesCount' => $coursesCount,
            'enrolledProgramsWithCourses' => $enrolledProgramsWithCourses,
            'learningTimeHours' => 48,
            'averageProgress' => 85,
        ]);
    }

    private function demoEnrolledData(): array
    {
        return [
            [
                'program' => [
                    'id' => 1,
                    'title' => 'برنامج تطوير الويب',
                    'thumbnail' => 'https://images.unsplash.com/photo-1675495666589-94cdafbcfcc8?w=400&h=225&fit=crop',
                    'slug' => 'web-dev',
                ],
                'courses' => [
                    [
                        'id' => 1,
                        'title' => 'بوت كامب تطوير الويب الكامل',
                        'thumbnail' => 'https://images.unsplash.com/photo-1675495666589-94cdafbcfcc8?w=400&h=225&fit=crop',
                        'instructor' => 'سارة جونسون',
                        'progress' => 65,
                        'total_lessons' => 42,
                        'completed_lessons' => 27,
                    ],
                ],
            ],
            [
                'program' => [
                    'id' => 2,
                    'title' => 'برنامج التصميم',
                    'thumbnail' => 'https://images.unsplash.com/photo-1510832758362-af875829efcf?w=400&h=225&fit=crop',
                    'slug' => 'design',
                ],
                'courses' => [
                    [
                        'id' => 2,
                        'title' => 'أساسيات تصميم واجهات المستخدم',
                        'thumbnail' => 'https://images.unsplash.com/photo-1510832758362-af875829efcf?w=400&h=225&fit=crop',
                        'instructor' => 'مايكل تشين',
                        'progress' => 30,
                        'total_lessons' => 35,
                        'completed_lessons' => 11,
                    ],
                ],
            ],
        ];
    }
}
