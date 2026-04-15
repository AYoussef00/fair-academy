<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreCourseRequest;
use App\Http\Requests\Instructor\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstructorCourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::query()
            ->forInstructor($request->user()->id)
            ->with('category:id,name')
            ->withCount('enrollments')
            ->latest()
            ->paginate(15);

        return view('instructor.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $this->authorize('create', Course::class);
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('instructor.courses.create', compact('categories'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['instructor_id'] = $request->user()->id;
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['status'] = $data['status'] ?? 'draft';

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        Course::create($data);

        return redirect()->route('instructor.courses.index')
            ->with('success', __('Course created successfully.'));
    }

    public function show(Course $course): View|RedirectResponse
    {
        $this->authorize('view', $course);
        $course->load(['category', 'modules' => fn ($q) => $q->orderBy('order_number')->with('lessons')]);

        return view('instructor.courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($data);

        return redirect()->route('instructor.courses.index')
            ->with('success', __('Course updated successfully.'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);
        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', __('Course deleted.'));
    }

    public function builder(Course $course): View
    {
        $this->authorize('update', $course);
        $course->load(['modules' => fn ($q) => $q->orderBy('order_number')->with(['lessons' => fn ($q) => $q->orderBy('order_number')])]);

        return view('instructor.courses.builder', compact('course'));
    }

    public function publish(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $course->update(['status' => $course->status === 'published' ? 'draft' : 'published']);

        return redirect()->back()->with('success', $course->status === 'published' ? __('Course published.') : __('Course unpublished.'));
    }
}
