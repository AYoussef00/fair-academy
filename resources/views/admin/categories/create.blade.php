@extends('layouts.admin')

@section('title', 'إضافة قسم')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">إضافة قسم جديد</h1>
    <p class="mt-1 text-slate-600 text-sm">مثال: ذكاء اصطناعي، تطوير ويب، تسويق رقمي</p>
</div>
<form action="{{ route('admin.categories.store') }}" method="POST" class="max-w-xl rounded-xl border border-slate-200 bg-white p-6">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-sm font-semibold text-slate-700">اسم القسم *</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
        <label for="description" class="block text-sm font-semibold text-slate-700">الوصف (اختياري)</label>
        <textarea id="description" name="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">{{ old('description') }}</textarea>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="rounded-lg bg-slate-800 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">إضافة</button>
        <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">إلغاء</a>
    </div>
</form>
@endsection
