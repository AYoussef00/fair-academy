@extends('layouts.instructor')

@section('title', 'جدولة فصل مباشر')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">جدولة فصل مباشر</h1>
</div>

<form action="{{ route('instructor.live-classes.store') }}" method="POST" class="bg-white rounded-xl shadow border border-gray-100 p-6 max-w-2xl">
    @csrf

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الدورة *</label>
        <select name="course_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
            <option value="">اختر الدورة</option>
            @foreach($courses as $c)
                <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
            @endforeach
        </select>
        @error('course_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الفصل *</label>
        <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الوقت *</label>
        <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('start_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">المدة (دقيقة)</label>
        <input type="number" name="duration" value="{{ old('duration', 60) }}" min="1" class="w-full rounded-lg border border-gray-300 px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">رابط الاجتماع</label>
        <input type="url" name="meeting_url" value="{{ old('meeting_url') }}" placeholder="https://..." class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('meeting_url')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">جدولة</button>
        <a href="{{ route('instructor.live-classes.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">إلغاء</a>
    </div>
</form>
@endsection
