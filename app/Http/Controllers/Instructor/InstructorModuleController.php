<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreModuleRequest;
use App\Http\Requests\Instructor\UpdateModuleRequest;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorModuleController extends Controller
{
    public function store(StoreModuleRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        unset($data['video']);
        $data['course_id'] = $course->id;
        $data['order_number'] = $data['order_number'] ?? ($course->modules()->max('order_number') + 1);

        if ($request->hasFile('video')) {
            $data['video_url'] = $request->file('video')->store('courses/modules', 'public');
        }

        Module::create($data);

        return redirect()->route('instructor.courses.builder', $course)
            ->with('success', __('Module added.'));
    }

    public function update(UpdateModuleRequest $request, Course $course, Module $module): RedirectResponse
    {
        $this->authorize('update', $course);
        if ((int) $module->course_id !== (int) $course->id) {
            abort(404);
        }

        $data = $request->validated();
        unset($data['video']);

        if ($request->hasFile('video')) {
            if ($module->video_url) {
                Storage::disk('public')->delete($module->video_url);
            }
            $data['video_url'] = $request->file('video')->store('courses/modules', 'public');
        }

        $module->update($data);

        return redirect()->route('instructor.courses.builder', $course)
            ->with('success', __('Module updated.'));
    }

    public function destroy(Course $course, Module $module): RedirectResponse
    {
        $this->authorize('update', $course);
        if ((int) $module->course_id !== (int) $course->id) {
            abort(404);
        }

        if ($module->video_url) {
            Storage::disk('public')->delete($module->video_url);
        }
        $module->delete();

        return redirect()->route('instructor.courses.builder', $course)
            ->with('success', __('Module deleted.'));
    }

    public function reorder(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $order = $request->input('order', []);
        if (! is_array($order)) {
            return redirect()->back()->with('error', __('Invalid order.'));
        }

        $moduleIds = $course->modules()->pluck('id')->toArray();
        foreach ($order as $position => $id) {
            if (in_array((int) $id, $moduleIds, true)) {
                Module::where('id', $id)->update(['order_number' => $position]);
            }
        }

        return redirect()->back()->with('success', __('Order saved.'));
    }
}
