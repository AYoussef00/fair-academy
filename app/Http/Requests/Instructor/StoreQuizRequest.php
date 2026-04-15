<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        $courseId = $this->input('course_id');
        if (! $courseId) {
            return false;
        }
        $course = \App\Models\Course::find($courseId);

        return $course && $this->user()?->can('update', $course);
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pass_mark' => ['nullable', 'integer', 'min:0', 'max:100'],
            'time_limit' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
