<?php

namespace App\Services\Instructor;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Collection;

class ProgressService
{
    public function getEnrollmentProgress(Enrollment $enrollment): float
    {
        $course = $enrollment->course;
        $course->loadMissing('modules.lessons');
        $lessonIds = $course->modules->flatMap->lessons->pluck('id');
        $totalLessons = $lessonIds->count();

        if ($totalLessons === 0) {
            return 0.0;
        }

        $completedCount = \App\Models\LessonProgress::query()
            ->where('user_id', $enrollment->user_id)
            ->where('completed', true)
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        return round((float) ($completedCount / $totalLessons * 100), 2);
    }

    public function recalculateAndSaveEnrollmentProgress(Enrollment $enrollment): float
    {
        $progress = $this->getEnrollmentProgress($enrollment);
        $enrollment->update(['progress' => $progress]);

        return $progress;
    }

    public function getCourseAverageCompletion(Course $course): float
    {
        $enrollments = $course->enrollments()->get();
        if ($enrollments->isEmpty()) {
            return 0.0;
        }

        $total = $enrollments->sum(fn ($e) => (float) $e->progress);

        return round($total / $enrollments->count(), 2);
    }

    public function getProgressForEnrollments(Collection $enrollments): array
    {
        $result = [];
        foreach ($enrollments as $e) {
            $result[$e->id] = $this->getEnrollmentProgress($e);
        }

        return $result;
    }
}
