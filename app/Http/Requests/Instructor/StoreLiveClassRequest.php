<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class StoreLiveClassRequest extends FormRequest
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
            'start_time' => ['required', 'date'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'meeting_url' => ['nullable', 'string', 'max:500'],
            'zoom_meeting_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}
