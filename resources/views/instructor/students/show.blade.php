@extends('layouts.instructor')

@section('title', 'ملف الطالب')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">ملف الطالب</h1>
    <p class="text-gray-600 mt-1">{{ $enrollment->user->name }} · {{ $enrollment->course->title }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow border border-gray-100 p-5">
        <h2 class="text-lg font-semibold mb-3">معلومات التسجيل</h2>
        <p><span class="text-gray-500">البريد:</span> {{ $enrollment->user->email }}</p>
        <p><span class="text-gray-500">تاريخ التسجيل:</span> {{ $enrollment->enrolled_at?->format('Y-m-d H:i') ?? '—' }}</p>
        <p><span class="text-gray-500">التقدم:</span> <strong>{{ number_format($enrollment->computed_progress ?? 0, 1) }}%</strong></p>
    </div>
    <div class="bg-white rounded-xl shadow border border-gray-100 p-5">
        <h2 class="text-lg font-semibold mb-3">نتائج الاختبارات</h2>
        @forelse($quizAttempts as $attempt)
            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                <span>{{ $attempt->quiz->title ?? '—' }}</span>
                <span class="font-medium">{{ $attempt->score !== null ? number_format((float) $attempt->score, 1) : '—' }}</span>
                <span class="text-sm {{ $attempt->quiz && $attempt->score !== null && (float) $attempt->score >= (float) ($attempt->quiz->pass_mark ?? 0) ? 'text-green-600' : 'text-red-600' }}">
                    {{ $attempt->quiz && $attempt->score !== null ? ((float) $attempt->score >= (float) ($attempt->quiz->pass_mark ?? 0) ? 'ناجح' : 'راسب') : '—' }}
                </span>
            </div>
        @empty
            <p class="text-gray-500">لا توجد محاولات اختبار</p>
        @endforelse
    </div>
</div>

<a href="{{ route('instructor.students.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">العودة للقائمة</a>
@endsection
