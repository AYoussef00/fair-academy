@extends('layouts.instructor')

@section('title', $course->title)

@section('content')
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $course->title }}</h1>
        <p class="text-gray-600 mt-1">{{ $course->category->name ?? '' }} · {{ $course->status }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('instructor.courses.builder', $course) }}" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">المنهج</a>
        <a href="{{ route('instructor.courses.edit', $course) }}" class="rounded-lg border border-gray-300 px-4 py-2 hover:bg-gray-50">تعديل</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">
    @if($course->description)
        <p class="text-gray-700 whitespace-pre-line">{{ $course->description }}</p>
    @else
        <p class="text-gray-500">لا يوجد وصف</p>
    @endif
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 p-6">
    <h2 class="text-lg font-semibold mb-4">الوحدات والدروس</h2>
    @forelse($course->modules as $module)
        <div class="mb-4 border border-gray-100 rounded-lg p-4">
            <p class="font-medium">{{ $module->title }}</p>
            <ul class="mt-2 me-4 space-y-1 text-sm text-gray-600">
                @foreach($module->lessons as $lesson)
                    <li>{{ $lesson->title }} ({{ $lesson->type }})</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-gray-500">لا توجد وحدات بعد. <a href="{{ route('instructor.courses.builder', $course) }}" class="text-violet-600">بناء المنهج</a></p>
    @endforelse
</div>
@endsection
