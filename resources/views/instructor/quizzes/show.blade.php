@extends('layouts.instructor')

@section('title', $quiz->title)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>
        <p class="text-gray-600 mt-1">{{ $quiz->course->title ?? '' }}</p>
    </div>
    <a href="{{ route('instructor.quizzes.edit', $quiz) }}" class="rounded-lg border border-gray-300 px-4 py-2 hover:bg-gray-50">تعديل</a>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">
    <h2 class="text-lg font-semibold mb-3">الأسئلة ({{ $quiz->questions->count() }})</h2>
    @forelse($quiz->questions as $q)
        <div class="mb-3 pb-3 border-b border-gray-100 last:border-0">
            <p class="font-medium">{{ $q->question_text }}</p>
            <ul class="text-sm text-gray-600 mt-1 me-4">
                @foreach($q->answers as $a)
                    <li>{{ $a->answer_text }} {{ $a->is_correct ? '✓' : '' }}</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-gray-500">لا توجد أسئلة بعد. يمكن إضافتها لاحقاً.</p>
    @endforelse
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <h2 class="text-lg font-semibold p-4 border-b border-gray-100">محاولات الطلاب</h2>
    <table class="w-full text-right">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4">الطالب</th>
                <th class="py-3 px-4">الدرجة</th>
                <th class="py-3 px-4">النسبة</th>
                <th class="py-3 px-4">تاريخ الانتهاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attempts as $a)
                <tr class="border-t border-gray-100">
                    <td class="py-3 px-4">{{ $a->user->name ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $a->score !== null ? number_format((float) $a->score, 1) : '—' }}</td>
                    <td class="py-3 px-4">{{ $a->score !== null && $quiz->pass_mark ? ( (float) $a->score >= (float) $quiz->pass_mark ? 'ناجح' : 'راسب' ) : '—' }}</td>
                    <td class="py-3 px-4">{{ $a->completed_at?->format('Y-m-d H:i') ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-6 text-center text-gray-500">لا توجد محاولات بعد</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($attempts->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $attempts->links() }}</div>
    @endif
</div>
@endsection
