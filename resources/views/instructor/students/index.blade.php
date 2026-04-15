@extends('layouts.instructor')

@section('title', 'الطلاب')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">الطلاب</h1>
    <form action="{{ route('instructor.students.index') }}" method="GET" class="flex gap-2">
        <select name="course_id" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2">
            <option value="">كل الدورات</option>
            @foreach($courses as $c)
                <option value="{{ $c->id }}" {{ ($selectedCourseId ?? '') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4">الطالب</th>
                <th class="py-3 px-4">الدورة</th>
                <th class="py-3 px-4">تاريخ التسجيل</th>
                <th class="py-3 px-4">التقدم %</th>
                <th class="py-3 px-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $e)
                <tr class="border-t border-gray-100">
                    <td class="py-3 px-4">{{ $e->user->name ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $e->course->title ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $e->enrolled_at?->format('Y-m-d') ?? '—' }}</td>
                    <td class="py-3 px-4">{{ number_format($e->computed_progress ?? 0, 1) }}%</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('instructor.students.show', $e->id) }}" class="text-violet-600 text-sm">عرض</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">لا يوجد طلاب مسجلون بعد</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($enrollments->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $enrollments->links() }}</div>
    @endif
</div>
@endsection
