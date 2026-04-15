<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLiveClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('live_class')->course ?? $this->route('course');

        return $course && $this->user()?->can('update', $course);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'start_time' => ['sometimes', 'date'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'meeting_url' => ['nullable', 'string', 'max:500'],
            'zoom_meeting_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}
