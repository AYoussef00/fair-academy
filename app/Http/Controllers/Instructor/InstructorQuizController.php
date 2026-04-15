<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreQuizRequest;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorQuizController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courses = Course::query()->forInstructor($user->id)->get(['id', 'title']);

        $quizzes = Quiz::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->with('course:id,title')
            ->withCount('quizAttempts')
            ->latest()
            ->paginate(15);

        return view('instructor.quizzes.index', compact('quizzes', 'courses'));
    }

    public function create(Request $request): View
    {
        $courses = Course::query()->forInstructor($request->user()->id)->get(['id', 'title']);

        return view('instructor.quizzes.create', compact('courses'));
    }

    public function store(StoreQuizRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['pass_mark'] = $data['pass_mark'] ?? 0;
        $data['time_limit'] = $data['time_limit'] ?? null;

        Quiz::create($data);

        return redirect()->route('instructor.quizzes.index')->with('success', __('Quiz created.'));
    }

    public function show(Quiz $quiz): View|RedirectResponse
    {
        $course = $quiz->course;
        $this->authorize('update', $course);

        $quiz->load(['course', 'questions.answers']);
        $attempts = $quiz->quizAttempts()->with('user:id,name,email')->latest()->paginate(20);

        return view('instructor.quizzes.show', compact('quiz', 'attempts'));
    }

    public function edit(Quiz $quiz): View
    {
        $this->authorize('update', $quiz->course);
        $quiz->load('questions.answers');

        return view('instructor.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);
        $quiz->update($request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pass_mark' => ['nullable', 'integer', 'min:0', 'max:100'],
            'time_limit' => ['nullable', 'integer', 'min:0'],
        ]));

        return redirect()->route('instructor.quizzes.show', $quiz)->with('success', __('Quiz updated.'));
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz->course);
        $quiz->delete();

        return redirect()->route('instructor.quizzes.index')->with('success', __('Quiz deleted.'));
    }
}
