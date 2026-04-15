<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LiveClass;
use App\Services\Instructor\ProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorDashboardController extends Controller
{
    public function __invoke(Request $request, ProgressService $progress): View
    {
        $user = $request->user();
        $instructorId = $user->id;

        $courses = Course::query()
            ->forInstructor($instructorId)
            ->withCount(['enrollments', 'modules'])
            ->get();

        $totalCourses = $courses->count();
        $totalEnrollments = $courses->sum('enrollments_count');

        $totalStudents = Enrollment::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');

        $avgCompletion = 0.0;
        if ($totalEnrollments > 0) {
            $enrollments = Enrollment::query()
                ->whereIn('course_id', $courses->pluck('id'))
                ->with('course.modules.lessons')
                ->get();
            $sum = 0;
            foreach ($enrollments as $e) {
                $sum += $progress->getEnrollmentProgress($e);
            }
            $avgCompletion = round($sum / $enrollments->count(), 1);
        }

        $upcomingLiveClasses = LiveClass::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->where('start_time', '>=', now())
            ->where('start_time', '<=', now()->addDays(7))
            ->with('course:id,title')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $recentEnrollments = Enrollment::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->with(['user:id,name,email', 'course:id,title'])
            ->latest('enrolled_at')
            ->limit(10)
            ->get();

        return view('instructor.dashboard', [
            'totalCourses' => $totalCourses,
            'totalStudents' => $totalStudents,
            'totalEnrollments' => $totalEnrollments,
            'averageCompletion' => $avgCompletion,
            'upcomingLiveClasses' => $upcomingLiveClasses,
            'recentEnrollments' => $recentEnrollments,
            'courses' => $courses,
        ]);
    }
}
