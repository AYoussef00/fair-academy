<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\Instructor\ProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorStudentController extends Controller
{
    public function index(Request $request, ProgressService $progress): View
    {
        $user = $request->user();
        $courses = Course::query()->forInstructor($user->id)->get(['id', 'title']);
        $courseId = $request->input('course_id');

        $query = Enrollment::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->with(['user:id,name,email', 'course:id,title']);

        if ($courseId && $courses->pluck('id')->contains((int) $courseId)) {
            $query->where('course_id', $courseId);
        }

        $enrollments = $query->with('course.modules.lessons')->latest('enrolled_at')->paginate(20);

        foreach ($enrollments as $e) {
            $e->computed_progress = $progress->getEnrollmentProgress($e);
        }

        return view('instructor.students.index', [
            'enrollments' => $enrollments,
            'courses' => $courses,
            'selectedCourseId' => $courseId,
        ]);
    }

    public function show(Request $request, int $enrollmentId, ProgressService $progress): View
    {
        $user = $request->user();
        $enrollment = Enrollment::query()
            ->whereIn('course_id', Course::query()->forInstructor($user->id)->pluck('id'))
            ->with(['user', 'course.modules.lessons', 'course.quizzes'])
            ->findOrFail($enrollmentId);

        $enrollment->computed_progress = $progress->getEnrollmentProgress($enrollment);

        $quizAttempts = \App\Models\QuizAttempt::query()
            ->where('user_id', $enrollment->user_id)
            ->whereIn('quiz_id', $enrollment->course->quizzes->pluck('id'))
            ->with('quiz:id,title,pass_mark')
            ->latest()
            ->get();

        return view('instructor.students.show', [
            'enrollment' => $enrollment,
            'quizAttempts' => $quizAttempts,
        ]);
    }
}
