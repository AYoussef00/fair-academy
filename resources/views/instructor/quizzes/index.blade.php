@extends('layouts.instructor')

@section('title', 'الاختبارات')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">الاختبارات</h1>
    <a href="{{ route('instructor.quizzes.create') }}" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">إنشاء اختبار</a>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4">العنوان</th>
                <th class="py-3 px-4">الدورة</th>
                <th class="py-3 px-4">المحاولات</th>
                <th class="py-3 px-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quizzes as $q)
                <tr class="border-t border-gray-100">
                    <td class="py-3 px-4 font-medium">{{ $q->title }}</td>
                    <td class="py-3 px-4">{{ $q->course->title ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $q->quiz_attempts_count ?? 0 }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('instructor.quizzes.show', $q) }}" class="text-violet-600 text-sm me-2">عرض</a>
                        <a href="{{ route('instructor.quizzes.edit', $q) }}" class="text-violet-600 text-sm me-2">تعديل</a>
                        <form action="{{ route('instructor.quizzes.destroy', $q) }}" method="POST" class="inline" onsubmit="return confirm('حذف الاختبار؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">لا توجد اختبارات. <a href="{{ route('instructor.quizzes.create') }}" class="text-violet-600">إنشاء اختبار</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($quizzes->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $quizzes->links() }}</div>
    @endif
</div>
@endsection
