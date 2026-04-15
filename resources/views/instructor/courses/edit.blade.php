@extends('layouts.instructor')

@section('title', 'تعديل الدورة')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">تعديل الدورة</h1>
    <p class="text-gray-600 mt-1">{{ $course->title }}</p>
</div>

<form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow border border-gray-100 p-6 max-w-2xl">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
        <select name="category_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            <option value="">— لا قسم —</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ old('category_id', $course->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
        @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الدورة *</label>
        <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
        <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('description', $course->description) }}</textarea>
        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">صورة مصغرة</label>
        @if($course->thumbnail)
            <p class="text-sm text-gray-500 mb-1">الحالية: <a href="{{ asset('storage/' . $course->thumbnail) }}" target="_blank" class="text-violet-600">عرض</a></p>
        @endif
        <input type="file" name="thumbnail" accept="image/*" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('thumbnail')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">السعر</label>
            <input type="number" name="price" value="{{ old('price', $course->price) }}" step="0.01" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">المدة</label>
            <input type="text" name="duration" value="{{ old('duration', $course->duration) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('duration')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>مسودة</option>
            <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>منشور</option>
        </select>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">حفظ التعديلات</button>
        <a href="{{ route('instructor.courses.builder', $course) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">المنهج</a>
        <a href="{{ route('instructor.courses.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">إلغاء</a>
    </div>
</form>
@endsection
