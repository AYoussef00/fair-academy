<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreLiveClassRequest;
use App\Http\Requests\Instructor\UpdateLiveClassRequest;
use App\Models\Course;
use App\Models\LiveClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorLiveClassController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::query()->forInstructor($request->user()->id)->pluck('id');

        $liveClasses = LiveClass::query()
            ->whereIn('course_id', $courses)
            ->with('course:id,title')
            ->orderBy('start_time')
            ->paginate(15);

        return view('instructor.live_classes.index', compact('liveClasses'));
    }

    public function create(Request $request): View
    {
        $courses = Course::query()->forInstructor($request->user()->id)->get(['id', 'title']);

        return view('instructor.live_classes.create', compact('courses'));
    }

    public function store(StoreLiveClassRequest $request): RedirectResponse
    {
        $data = $request->validated();

        LiveClass::create($data);

        return redirect()->route('instructor.live-classes.index')->with('success', __('Live class scheduled.'));
    }

    public function edit(LiveClass $liveClass): View
    {
        $this->authorize('update', $liveClass->course);
        $liveClass->load('course');

        return view('instructor.live_classes.edit', compact('liveClass'));
    }

    public function update(UpdateLiveClassRequest $request, LiveClass $liveClass): RedirectResponse
    {
        $this->authorize('update', $liveClass->course);
        $liveClass->update($request->validated());

        return redirect()->route('instructor.live-classes.index')->with('success', __('Live class updated.'));
    }

    public function destroy(LiveClass $liveClass): RedirectResponse
    {
        $this->authorize('update', $liveClass->course);
        $liveClass->delete();

        return redirect()->route('instructor.live-classes.index')->with('success', __('Live class deleted.'));
    }
}
