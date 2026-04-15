@extends('layouts.instructor')

@section('title', 'تعديل الدرس - ' . $lesson->title)

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">تعديل الدرس</h1>
    <p class="text-gray-600 mt-1">{{ $course->title }} · {{ $lesson->module->title }}</p>
</div>

<form action="{{ route('instructor.lessons.update', [$course, $lesson]) }}" method="POST" class="bg-white rounded-xl shadow border border-gray-100 p-6 max-w-2xl">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الوحدة</label>
        <select name="module_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            @foreach($course->modules as $m)
                <option value="{{ $m->id }}" {{ old('module_id', $lesson->module_id) == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الدرس *</label>
        <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
        <select name="type" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            <option value="video" {{ old('type', $lesson->type) === 'video' ? 'selected' : '' }}>فيديو</option>
            <option value="article" {{ old('type', $lesson->type) === 'article' ? 'selected' : '' }}>مقال</option>
            <option value="live" {{ old('type', $lesson->type) === 'live' ? 'selected' : '' }}>مباشر</option>
            <option value="file" {{ old('type', $lesson->type) === 'file' ? 'selected' : '' }}>ملف</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">رابط الفيديو</label>
        <input type="text" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">المحتوى</label>
        <textarea name="content" rows="6" class="w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('content', $lesson->content) }}</textarea>
    </div>

    <div class="flex gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">المدة (دقيقة)</label>
            <input type="number" name="duration" value="{{ old('duration', $lesson->duration) }}" min="0" class="rounded-lg border border-gray-300 px-3 py-2 w-24">
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-2"><input type="checkbox" name="is_free" value="1" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}> درس مجاني</label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">حفظ</button>
        <a href="{{ route('instructor.courses.builder', $course) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">العودة للمنهج</a>
    </div>
</form>
@endsection
