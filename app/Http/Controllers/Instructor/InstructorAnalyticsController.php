<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use App\Services\Instructor\ProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorAnalyticsController extends Controller
{
    public function index(Request $request, ProgressService $progress): View
    {
        $user = $request->user();
        $courses = Course::query()
            ->forInstructor($user->id)
            ->withCount('enrollments')
            ->with('enrollments')
            ->get();

        $studentsPerCourse = $courses->map(fn ($c) => [
            'course' => $c->title,
            'students' => $c->enrollments_count,
        ]);

        $completionPerCourse = [];
        foreach ($courses as $course) {
            $completionPerCourse[] = [
                'course' => $course->title,
                'completion' => $progress->getCourseAverageCompletion($course),
            ];
        }

        $enrollmentIds = $courses->pluck('id');
        $enrollmentsPerMonth = Enrollment::query()
            ->whereIn('course_id', $enrollmentIds)
            ->selectRaw('DATE_FORMAT(enrolled_at, "%Y-%m") as month, count(*) as total')
            ->whereNotNull('enrolled_at')
            ->where('enrolled_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $quizzes = \App\Models\Quiz::query()->whereIn('course_id', $courses->pluck('id'))->get(['id', 'title', 'pass_mark']);
        $quizPerformance = QuizAttempt::query()
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->whereNotNull('score')
            ->whereNotNull('completed_at')
            ->with('quiz:id,pass_mark')
            ->get()
            ->groupBy('quiz_id')
            ->map(function ($attempts) {
                $passMark = (int) ($attempts->first()->quiz->pass_mark ?? 0);
                $passed = $attempts->filter(fn ($a) => (float) $a->score >= $passMark)->count();

                return [
                    'total' => $attempts->count(),
                    'passed' => $passed,
                    'failed' => $attempts->count() - $passed,
                ];
            });

        return view('instructor.analytics.index', [
            'studentsPerCourse' => $studentsPerCourse,
            'completionPerCourse' => $completionPerCourse,
            'enrollmentsPerMonth' => $enrollmentsPerMonth,
            'quizPerformance' => $quizPerformance,
            'courses' => $courses,
        ]);
    }
}
