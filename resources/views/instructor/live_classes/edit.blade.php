@extends('layouts.instructor')

@section('title', 'تعديل الفصل المباشر')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">تعديل الفصل المباشر</h1>
    <p class="text-gray-600 mt-1">{{ $liveClass->title }} · {{ $liveClass->course->title ?? '' }}</p>
</div>

<form action="{{ route('instructor.live-classes.update', $liveClass) }}" method="POST" class="bg-white rounded-xl shadow border border-gray-100 p-6 max-w-2xl">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الفصل *</label>
        <input type="text" name="title" value="{{ old('title', $liveClass->title) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الوقت *</label>
        <input type="datetime-local" name="start_time" value="{{ old('start_time', $liveClass->start_time?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('start_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">المدة (دقيقة)</label>
        <input type="number" name="duration" value="{{ old('duration', $liveClass->duration) }}" min="1" class="w-full rounded-lg border border-gray-300 px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">رابط الاجتماع</label>
        <input type="url" name="meeting_url" value="{{ old('meeting_url', $liveClass->meeting_url) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">حفظ</button>
        <a href="{{ route('instructor.live-classes.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">إلغاء</a>
    </div>
</form>
@endsection
