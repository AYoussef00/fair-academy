<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreLessonRequest;
use App\Http\Requests\Instructor\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstructorLessonController extends Controller
{
    public function store(StoreLessonRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $module = Module::where('id', $data['module_id'])->where('course_id', $course->id)->firstOrFail();
        $data['order_number'] = $data['order_number'] ?? ($module->lessons()->max('order_number') + 1);
        $data['is_free'] = $request->boolean('is_free', false);
        unset($data['video']);

        if ($request->hasFile('video')) {
            $data['video_url'] = $request->file('video')->store('courses/lessons', 'public');
        }

        $lesson = Lesson::create($data);

        return redirect()->route('instructor.courses.builder', $course)
            ->with('success', __('Lesson added.'))
            ->with('new_lesson_id', $lesson->id)
            ->with('new_lesson_module_id', $module->id);
    }

    public function edit(Course $course, Lesson $lesson): View
    {
        $this->authorize('update', $course);
        $lesson->load('module');
        if ((int) $lesson->module->course_id !== (int) $course->id) {
            abort(404);
        }
        $course->load('modules');

        return view('instructor.lessons.edit', compact('course', 'lesson'));
    }

    public function update(UpdateLessonRequest $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $course);
        $lesson->load('module');
        if ((int) $lesson->module->course_id !== (int) $course->id) {
            abort(404);
        }

        $data = $request->validated();
        $data['is_free'] = $request->boolean('is_free', $lesson->is_free);
        unset($data['video']);

        if ($request->hasFile('video')) {
            if ($lesson->video_url && ! str_starts_with($lesson->video_url, 'http')) {
                Storage::disk('public')->delete($lesson->video_url);
            }

            $data['video_url'] = $request->file('video')->store('courses/lessons', 'public');
        }

        $lesson->update($data);

        return redirect()->route('instructor.courses.builder', $course)
            ->with('success', __('Lesson updated.'));
    }

    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $course);
        $lesson->load('module');
        if ((int) $lesson->module->course_id !== (int) $course->id) {
            abort(404);
        }

        if ($lesson->video_url && ! str_starts_with($lesson->video_url, 'http')) {
            Storage::disk('public')->delete($lesson->video_url);
        }

        $lesson->delete();

        return redirect()->route('instructor.courses.builder', $course)
            ->with('success', __('Lesson deleted.'));
    }

    public function reorder(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $order = $request->input('order', []);
        if (! is_array($order)) {
            return redirect()->back()->with('error', __('Invalid order.'));
        }

        $lessonIds = $course->modules->pluck('lessons')->flatten()->pluck('id')->toArray();
        foreach ($order as $position => $id) {
            if (in_array((int) $id, $lessonIds, true)) {
                Lesson::where('id', $id)->update(['order_number' => $position]);
            }
        }

        return redirect()->back()->with('success', __('Order saved.'));
    }
}
