@extends('layouts.instructor')

@section('title', 'لوحة التحكم')

@section('content')
{{-- غلاف يمنع تجاوز المحتوى (min-w-0) — كل المحتوى ظاهر داخل العرض --}}
<div class="min-w-0 w-full overflow-hidden">
{{-- ترحيب + إحصائيات + زر: متجاوب — على الموبايل يظهر الترحيب ثم صف البطاقات ثم الزر --}}
<div class="border-b border-slate-200 pb-4">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between min-w-0">
        <div class="shrink-0 min-w-0">
            <h1 class="text-lg font-bold text-slate-900 sm:text-xl">أهلاً، {{ auth()->user()->name }}</h1>
            <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">نظرة عامة على دوراتك وطلابك</p>
        </div>
        <a href="{{ route('instructor.courses.create') }}" class="order-last w-full shrink-0 rounded-xl bg-violet-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-violet-700 sm:order-none sm:w-auto">دورة جديدة</a>
    </div>
    {{-- بطاقات الإحصائيات: شبكة تتقلص داخل العرض (min-w-0 على الشبكة والعناصر) --}}
    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 min-w-0">
        <div class="flex min-h-[4.5rem] min-w-0 flex-col items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm text-center sm:min-h-[5rem] sm:px-4 sm:py-4">
            <span class="text-xl font-bold tabular-nums text-slate-900 sm:text-2xl">{{ $totalCourses }}</span>
            <span class="mt-1 text-xs font-medium text-slate-500 sm:mt-1.5 sm:text-sm">الدورات</span>
        </div>
        <div class="flex min-h-[4.5rem] min-w-0 flex-col items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm text-center sm:min-h-[5rem] sm:px-4 sm:py-4">
            <span class="text-xl font-bold tabular-nums text-slate-900 sm:text-2xl">{{ $totalStudents }}</span>
            <span class="mt-1 text-xs font-medium text-slate-500 sm:mt-1.5 sm:text-sm">الطلاب</span>
        </div>
        <div class="flex min-h-[4.5rem] min-w-0 flex-col items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm text-center sm:min-h-[5rem] sm:px-4 sm:py-4">
            <span class="text-xl font-bold tabular-nums text-slate-900 sm:text-2xl">{{ $totalEnrollments }}</span>
            <span class="mt-1 text-xs font-medium text-slate-500 sm:mt-1.5 sm:text-sm">التسجيلات</span>
        </div>
        <div class="flex min-h-[4.5rem] min-w-0 flex-col items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm text-center sm:min-h-[5rem] sm:px-4 sm:py-4">
            <span class="text-xl font-bold tabular-nums text-slate-900 sm:text-2xl">{{ number_format($averageCompletion, 1) }}%</span>
            <span class="mt-1 text-xs font-medium text-slate-500 sm:mt-1.5 sm:text-sm">الإكمال</span>
        </div>
    </div>
</div>

{{-- 3 جداول: min-w-0 حتى لا تتجاوز العرض --}}
<div class="mt-4 grid grid-cols-1 gap-4 sm:gap-5 lg:grid-cols-3 min-w-0">
    {{-- جدول الفصول المباشرة --}}
    <div class="flex min-w-0 flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex shrink-0 items-center justify-between border-b border-slate-200 bg-slate-50 px-3 py-2">
            <h2 class="text-sm font-semibold text-slate-800">الفصول المباشرة</h2>
            <a href="{{ route('instructor.live-classes.index') }}" class="text-xs font-medium text-violet-600 hover:underline">الكل</a>
        </div>
        <div class="min-h-0 flex-1 overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80">
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">العنوان</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">الدورة</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">الوقت</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">رابط</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingLiveClasses as $lc)
                        <tr class="border-b border-slate-100 last:border-0">
                            <td class="px-3 py-2 font-medium text-slate-800">{{ $lc->title }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $lc->course->title ?? '—' }}</td>
                            <td class="px-3 py-2 text-slate-500 text-xs">{{ $lc->start_time?->format('d/m H:i') }}</td>
                            <td class="px-3 py-2">
                                @if($lc->meeting_url ?? $lc->zoom_meeting_id)
                                    <a href="{{ $lc->meeting_url ?? '#' }}" target="_blank" class="text-xs font-medium text-violet-600 hover:underline">رابط</a>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-4 text-center text-xs text-slate-500">لا توجد فصول قادمة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- جدول آخر التسجيلات --}}
    <div class="flex min-w-0 flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex shrink-0 items-center justify-between border-b border-slate-200 bg-slate-50 px-3 py-2">
            <h2 class="text-sm font-semibold text-slate-800">آخر التسجيلات</h2>
            <a href="{{ route('instructor.students.index') }}" class="text-xs font-medium text-violet-600 hover:underline">الكل</a>
        </div>
        <div class="min-h-0 flex-1 overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80">
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">الطالب</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">الدورة</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEnrollments as $e)
                        <tr class="border-b border-slate-100 last:border-0">
                            <td class="px-3 py-2 font-medium text-slate-800">{{ $e->user->name ?? '—' }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $e->course->title ?? '—' }}</td>
                            <td class="px-3 py-2 text-slate-500 text-xs">{{ $e->enrolled_at?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-3 py-4 text-center text-xs text-slate-500">لا توجد تسجيلات حديثة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- جدول دوراتي --}}
    <div class="flex min-w-0 flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex shrink-0 items-center justify-between border-b border-slate-200 bg-slate-50 px-3 py-2">
            <h2 class="text-sm font-semibold text-slate-800">دوراتي</h2>
            <a href="{{ route('instructor.courses.index') }}" class="text-xs font-medium text-violet-600 hover:underline">الكل</a>
        </div>
        <div class="min-h-0 flex-1 overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80">
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">الدورة</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">المسجلون</th>
                        <th class="px-3 py-2 text-xs font-semibold text-slate-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $c)
                        <tr class="border-b border-slate-100 last:border-0">
                            <td class="px-3 py-2 font-medium text-slate-800">{{ $c->title }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $c->enrollments_count ?? 0 }}</td>
                            <td class="px-3 py-2">
                                <a href="{{ route('instructor.courses.builder', $c) }}" class="text-xs font-medium text-violet-600 hover:underline">المنهج</a>
                                <span class="text-slate-300">·</span>
                                <a href="{{ route('instructor.courses.edit', $c) }}" class="text-xs text-slate-600 hover:underline">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-3 py-4 text-center text-xs text-slate-500">لا توجد دورات. <a href="{{ route('instructor.courses.create') }}" class="font-medium text-violet-600 hover:underline">إنشاء دورة</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- روابط سريعة: تلف وتتجاوب مع الشاشات الصغيرة ولا يُخفى أي تاب --}}
<div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-200 pt-4 min-w-0">
    <a href="{{ route('instructor.courses.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 shadow-sm hover:bg-slate-50 sm:text-sm">دوراتي</a>
    <a href="{{ route('instructor.students.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 shadow-sm hover:bg-slate-50 sm:text-sm">الطلاب</a>
    <a href="{{ route('instructor.quizzes.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 shadow-sm hover:bg-slate-50 sm:text-sm">الاختبارات</a>
    <a href="{{ route('instructor.live-classes.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 shadow-sm hover:bg-slate-50 sm:text-sm">الفصول المباشرة</a>
    <a href="{{ route('instructor.analytics.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 shadow-sm hover:bg-slate-50 sm:text-sm">التحليلات</a>
</div>
</div>
@endsection
