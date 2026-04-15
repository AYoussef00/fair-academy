@extends('layouts.instructor')

@section('title', 'إنشاء اختبار')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">إنشاء اختبار جديد</h1>
</div>

<form action="{{ route('instructor.quizzes.store') }}" method="POST" class="bg-white rounded-xl shadow border border-gray-100 p-6 max-w-2xl">
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
        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الاختبار *</label>
        <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
        <textarea name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('description') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">درجة النجاح</label>
            <input type="number" name="pass_mark" value="{{ old('pass_mark', 60) }}" min="0" max="100" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الوقت (دقيقة)</label>
            <input type="number" name="time_limit" value="{{ old('time_limit') }}" min="0" placeholder="اختياري" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">إنشاء</button>
        <a href="{{ route('instructor.quizzes.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">إلغاء</a>
    </div>
</form>
@endsection
