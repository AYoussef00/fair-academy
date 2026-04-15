<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Support\HtmlSanitizer;
use App\Support\SafeVideoUrl;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function showLesson(Request $request, int $courseId, int $lessonId)
    {
        $course = Course::with(['instructor', 'modules' => fn ($q) => $q->orderBy('order_number')->with(['lessons' => fn ($q) => $q->orderBy('order_number')])])
            ->find($courseId);

        if ($course) {
            $lesson = Lesson::where('id', $lessonId)
                ->whereHas('module', fn ($q) => $q->where('course_id', $courseId))
                ->with('module')
                ->first();

            if (! $lesson) {
                abort(404);
            }

            $courseData = [
                'id' => $course->id,
                'title' => $course->title,
                'modules' => $course->modules->map(fn ($m) => [
                    'id' => $m->id,
                    'title' => $m->title,
                    'order_number' => $m->order_number,
                    'lessons' => $m->lessons->map(fn ($l) => [
                        'id' => $l->id,
                        'title' => $l->title,
                        'type' => $l->type,
                        'duration' => $l->duration,
                        'order_number' => $l->order_number,
                    ]),
                ]),
            ];
            $lessonData = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type,
                'video_url' => SafeVideoUrl::forFrontend($lesson->video_url),
                'content' => HtmlSanitizer::sanitize($lesson->content),
                'duration' => $lesson->duration,
            ];

            $lessonIds = $course->modules->pluck('lessons')->flatten()->pluck('id');
            $completedLessonIds = $request->user()
                ? \App\Models\LessonProgress::where('user_id', $request->user()->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('completed', true)
                    ->pluck('lesson_id')
                    ->values()
                    ->all()
                : [];
        } else {
            $demo = $this->getDemoCourseAndLesson($courseId, $lessonId);
            if (! $demo) {
                abort(404);
            }
            $courseData = $demo['course'];
            $lessonData = $demo['lesson'];
            $completedLessonIds = $demo['completed_lesson_ids'] ?? [];
        }

        return Inertia::render('Lesson', [
            'course' => $courseData,
            'lesson' => $lessonData,
            'completed_lesson_ids' => $completedLessonIds,
        ]);
    }

    public function showModule(Request $request, int $courseId, int $moduleId)
    {
        $course = Course::with(['instructor', 'modules' => fn ($q) => $q->orderBy('order_number')->with(['lessons' => fn ($q) => $q->orderBy('order_number')])])
            ->find($courseId);

        if (! $course) {
            abort(404);
        }

        $user = $request->user();
        if (! $user || ! $course->enrollments()->where('user_id', $user->id)->exists()) {
            return redirect()->route('course.show', $courseId);
        }

        $module = Module::where('id', $moduleId)
            ->where('course_id', $courseId)
            ->first();

        if (! $module) {
            abort(404);
        }

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'modules' => $course->modules->map(fn ($m) => [
                'id' => $m->id,
                'title' => $m->title,
                'order_number' => $m->order_number,
                'video_url' => $m->video_url ? (str_starts_with($m->video_url, 'http') ? SafeVideoUrl::forFrontend($m->video_url) : asset('storage/'.$m->video_url)) : null,
                'lessons' => $m->lessons->map(fn ($l) => [
                    'id' => $l->id,
                    'title' => $l->title,
                    'type' => $l->type,
                    'duration' => $l->duration,
                    'order_number' => $l->order_number,
                ]),
            ]),
        ];

        $moduleVideoUrl = $module->video_url
            ? (str_starts_with($module->video_url, 'http') ? SafeVideoUrl::forFrontend($module->video_url) : asset('storage/'.$module->video_url))
            : null;

        $moduleData = [
            'id' => $module->id,
            'title' => $module->title,
            'description' => $module->description,
            'video_url' => $moduleVideoUrl,
            'order_number' => $module->order_number,
        ];

        return Inertia::render('CourseModule', [
            'course' => $courseData,
            'module' => $moduleData,
        ]);
    }

    public function show(Request $request, int $id)
    {
        $course = Course::with(['instructor', 'category', 'modules' => fn ($q) => $q->orderBy('order_number')->with(['lessons' => fn ($q) => $q->orderBy('order_number')])])
            ->find($id);

        if (! $course) {
            return $this->demoCourse($id, $request);
        }

        $user = $request->user();
        $enrollment = $user
            ? $course->enrollments()->where('user_id', $user->id)->first()
            : null;

        $courseData = [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'thumbnail' => $course->thumbnail,
            'duration' => $course->duration,
            'price' => (float) $course->price,
            'status' => $course->status,
            'updated_at' => $course->updated_at?->toIso8601String(),
            'category' => $course->category ? [
                'id' => $course->category->id,
                'name' => $course->category->name,
            ] : null,
            'instructor' => $course->instructor ? [
                'name' => $course->instructor->name,
            ] : null,
            'modules' => $course->modules->map(fn ($m) => [
                'id' => $m->id,
                'title' => $m->title,
                'description' => $m->description,
                'order_number' => $m->order_number,
                'lessons' => $m->lessons->map(fn ($l) => [
                    'id' => $l->id,
                    'title' => $l->title,
                    'type' => $l->type,
                    'duration' => $l->duration,
                    'order_number' => $l->order_number,
                    'is_free' => $l->is_free ?? false,
                ]),
            ]),
        ];

        return Inertia::render('Course', [
            'course' => $courseData,
            'authUser' => $user ? ['id' => $user->id, 'name' => $user->name] : null,
            'enrolled' => (bool) $enrollment,
            'progress' => $enrollment ? (float) $enrollment->progress : 0,
            'completed_lessons' => $enrollment ? $this->completedLessonsCount($user->id, $course) : 0,
        ]);
    }

    private function completedLessonsCount(int $userId, Course $course): int
    {
        $lessonIds = $course->modules->pluck('lessons')->flatten()->pluck('id');

        return \App\Models\LessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $lessonIds)
            ->where('completed', true)
            ->count();
    }

    private function demoCourse(int $id, Request $request)
    {
        $demos = [
            1 => [
                'id' => 1,
                'title' => 'Complete Web Development Bootcamp',
                'description' => 'Learn HTML, CSS, JavaScript, and modern frameworks. Build real-world projects and become a full-stack developer.',
                'thumbnail' => 'https://images.unsplash.com/photo-1675495666589-94cdafbcfcc8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcm9ncmFtbWluZyUyMGNvZGluZyUyMGxhcHRvcHxlbnwxfHx8fDE3NzI4OTM1NjN8MA&ixlib=rb-4.1.0&q=80&w=1080',
                'duration' => '12 hours',
                'instructor' => ['name' => 'Sarah Johnson'],
                'modules' => [
                    ['id' => 1, 'title' => 'Getting Started', 'description' => 'Setup and basics', 'order_number' => 1, 'lessons' => [
                        ['id' => 1, 'title' => 'Welcome & Overview', 'type' => 'video', 'duration' => 10, 'order_number' => 1, 'is_free' => true],
                        ['id' => 2, 'title' => 'Setting up your environment', 'type' => 'video', 'duration' => 25, 'order_number' => 2, 'is_free' => false],
                        ['id' => 3, 'title' => 'HTML fundamentals', 'type' => 'video', 'duration' => 45, 'order_number' => 3, 'is_free' => false],
                    ]],
                    ['id' => 2, 'title' => 'CSS & Layout', 'description' => 'Styling and responsive design', 'order_number' => 2, 'lessons' => [
                        ['id' => 4, 'title' => 'Introduction to CSS', 'type' => 'video', 'duration' => 35, 'order_number' => 1, 'is_free' => false],
                        ['id' => 5, 'title' => 'Flexbox and Grid', 'type' => 'video', 'duration' => 50, 'order_number' => 2, 'is_free' => false],
                    ]],
                    ['id' => 3, 'title' => 'JavaScript Basics', 'description' => 'Core JavaScript', 'order_number' => 3, 'lessons' => [
                        ['id' => 6, 'title' => 'Variables and types', 'type' => 'video', 'duration' => 30, 'order_number' => 1, 'is_free' => false],
                        ['id' => 7, 'title' => 'Functions and scope', 'type' => 'video', 'duration' => 40, 'order_number' => 2, 'is_free' => false],
                    ]],
                ],
            ],
            2 => [
                'id' => 2,
                'title' => 'UI/UX Design Fundamentals',
                'description' => 'Master the principles of user interface and experience design. Create beautiful and usable digital products.',
                'thumbnail' => 'https://images.unsplash.com/photo-1510832758362-af875829efcf?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkZXNpZ24lMjBjcmVhdGl2ZSUyMHdvcmtzcGFjZXxlbnwxfHx8fDE3NzI4MzE0NjR8MA&ixlib=rb-4.1.0&q=80&w=1080',
                'duration' => '8 hours',
                'instructor' => ['name' => 'Michael Chen'],
                'modules' => [
                    ['id' => 4, 'title' => 'Design Principles', 'description' => 'Core principles', 'order_number' => 1, 'lessons' => [
                        ['id' => 8, 'title' => 'What is UI/UX?', 'type' => 'video', 'duration' => 15, 'order_number' => 1, 'is_free' => true],
                        ['id' => 9, 'title' => 'Visual hierarchy', 'type' => 'video', 'duration' => 28, 'order_number' => 2, 'is_free' => false],
                    ]],
                    ['id' => 5, 'title' => 'Wireframing', 'description' => 'Sketching and wireframes', 'order_number' => 2, 'lessons' => [
                        ['id' => 10, 'title' => 'Low-fidelity wireframes', 'type' => 'video', 'duration' => 35, 'order_number' => 1, 'is_free' => false],
                    ]],
                ],
            ],
        ];

        $course = $demos[$id] ?? null;
        if (! $course) {
            abort(404);
        }

        $progress = $id === 1 ? 65 : 30;
        $totalLessons = count(array_merge(...array_map(fn ($m) => $m['lessons'], $course['modules'])));
        $completedLessons = (int) round($totalLessons * $progress / 100);
        $user = $request->user();

        return Inertia::render('Course', [
            'course' => array_merge($course, [
                'category' => null,
                'price' => 0,
                'status' => 'published',
                'updated_at' => null,
            ]),
            'authUser' => $user ? ['id' => $user->id, 'name' => $user->name] : null,
            'enrolled' => false,
            'progress' => (float) $progress,
            'completed_lessons' => $completedLessons,
        ]);
    }

    private function getDemoCourseAndLesson(int $courseId, int $lessonId): ?array
    {
        $demos = [
            1 => [
                'id' => 1,
                'title' => 'Complete Web Development Bootcamp',
                'modules' => [
                    ['id' => 1, 'title' => 'Getting Started', 'order_number' => 1, 'lessons' => [
                        ['id' => 1, 'title' => 'Welcome & Overview', 'type' => 'video', 'duration' => 10, 'order_number' => 1, 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'content' => null],
                        ['id' => 2, 'title' => 'Setting up your environment', 'type' => 'video', 'duration' => 25, 'order_number' => 2, 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'content' => null],
                        ['id' => 3, 'title' => 'HTML fundamentals', 'type' => 'video', 'duration' => 45, 'order_number' => 3, 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'content' => null],
                    ]],
                    ['id' => 2, 'title' => 'CSS & Layout', 'order_number' => 2, 'lessons' => [
                        ['id' => 4, 'title' => 'Introduction to CSS', 'type' => 'video', 'duration' => 35, 'order_number' => 1, 'video_url' => null, 'content' => null],
                        ['id' => 5, 'title' => 'Flexbox and Grid', 'type' => 'video', 'duration' => 50, 'order_number' => 2, 'video_url' => null, 'content' => null],
                    ]],
                    ['id' => 3, 'title' => 'JavaScript Basics', 'order_number' => 3, 'lessons' => [
                        ['id' => 6, 'title' => 'Variables and types', 'type' => 'video', 'duration' => 30, 'order_number' => 1, 'video_url' => null, 'content' => null],
                        ['id' => 7, 'title' => 'Functions and scope', 'type' => 'video', 'duration' => 40, 'order_number' => 2, 'video_url' => null, 'content' => null],
                    ]],
                ],
            ],
            2 => [
                'id' => 2,
                'title' => 'UI/UX Design Fundamentals',
                'modules' => [
                    ['id' => 4, 'title' => 'Design Principles', 'order_number' => 1, 'lessons' => [
                        ['id' => 8, 'title' => 'What is UI/UX?', 'type' => 'video', 'duration' => 15, 'order_number' => 1, 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'content' => null],
                        ['id' => 9, 'title' => 'Visual hierarchy', 'type' => 'video', 'duration' => 28, 'order_number' => 2, 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'content' => null],
                    ]],
                    ['id' => 5, 'title' => 'Wireframing', 'order_number' => 2, 'lessons' => [
                        ['id' => 10, 'title' => 'Low-fidelity wireframes', 'type' => 'video', 'duration' => 35, 'order_number' => 1, 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'content' => null],
                    ]],
                ],
            ],
        ];

        $courseData = $demos[$courseId] ?? null;
        if (! $courseData) {
            return null;
        }

        $lessonData = null;
        foreach ($courseData['modules'] as $module) {
            foreach ($module['lessons'] as $l) {
                if ((int) $l['id'] === $lessonId) {
                    $lessonData = [
                        'id' => $l['id'],
                        'title' => $l['title'],
                        'type' => $l['type'],
                        'video_url' => $l['video_url'] ?? null,
                        'content' => $l['content'],
                        'duration' => $l['duration'],
                    ];
                    break 2;
                }
            }
        }

        if (! $lessonData) {
            return null;
        }

        $firstLessonId = $courseData['modules'][0]['lessons'][0]['id'];
        $completedLessonIds = [$firstLessonId];

        return [
            'course' => $courseData,
            'lesson' => $lessonData,
            'completed_lesson_ids' => $completedLessonIds,
        ];
    }
}
