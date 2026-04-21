<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function toggleStatus(Course $course): RedirectResponse
    {
        $newStatus = $course->status === 'published' ? 'draft' : 'published';

        $course->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', $newStatus === 'published'
                ? 'تم نشر الدورة بنجاح.'
                : 'تم إيقاف نشر الدورة بنجاح.');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/CoursesCreate', [
            'categories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                ])
                ->values(),
            'programs' => Program::query()
                ->orderBy('title')
                ->get(['id', 'title'])
                ->map(fn (Program $program) => [
                    'id' => $program->id,
                    'title' => $program->title,
                ])
                ->values(),
            'instructors' => User::query()
                ->where(function ($query): void {
                    $query->where('role', 'teacher')
                        ->orWhereHas('roles', fn ($roles) => $roles->where('name', 'teacher'));
                })
                ->orderBy('name')
                ->get(['id', 'name', 'email'])
                ->map(fn (User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ])
                ->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'program_id' => ['nullable', 'exists:programs,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'instructor_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'duration' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
        ], [
            'thumbnail.image' => 'صورة الغلاف يجب أن تكون ملف صورة صالح.',
            'thumbnail.max' => 'حجم صورة الغلاف يجب ألا يتجاوز 2 ميجابايت.',
            'thumbnail.uploaded' => 'فشل رفع صورة الغلاف من السيرفر. جرّب صورة أصغر ثم أعد المحاولة.',
        ]);

        $slug = Str::slug((string) $data['title']);
        $baseSlug = $slug !== '' ? $slug : 'course';
        $slug = $baseSlug;
        $counter = 2;

        while (Course::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $data['slug'] = $slug;
        $data['price'] = (float) ($data['price'] ?? 0);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        Course::create($data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'تم إنشاء الدورة الجديدة بنجاح.');
    }
}
