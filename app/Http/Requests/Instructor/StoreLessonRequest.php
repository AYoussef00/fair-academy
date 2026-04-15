<?php

namespace App\Http\Requests\Instructor;

use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('course')) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('content')) {
            $this->merge(['content' => HtmlSanitizer::sanitize($this->input('content'))]);
        }
    }

    public function rules(): array
    {
        return [
            'module_id' => ['required', 'exists:modules,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['video', 'article', 'live', 'file'])],
            'video_url' => [
                'nullable',
                'url:https',
                'max:500',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $host = parse_url($value, PHP_URL_HOST);
                    if ($host === false || $host === null) {
                        $fail(__('validation.url'));

                        return;
                    }
                    $allowed = array_map('strtolower', config('video.allowed_embed_hosts', []));
                    if (! in_array(strtolower($host), $allowed, true)) {
                        $fail('يجب أن يكون رابط الفيديو من أحد المواقع المسموحة (مثل يوتيوب أو فيمو) وببروتوكول https.');
                    }
                },
            ],
            'content' => ['nullable', 'string'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'order_number' => ['nullable', 'integer', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
        ];
    }
}
