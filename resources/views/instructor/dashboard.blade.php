@extends('layouts.instructor')

@section('title', 'لوحة التحكم')

@section('content')
<div class="min-w-0 space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">لوحة تحكم الأكاديمي</h1>
                <p class="mt-1 text-sm text-slate-500">مرحبًا {{ auth()->user()->name }}، هذه نظرة منظمة على دوراتك وطلابك ونشاطك.</p>
            </div>
            <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#ed9134] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#d67d2a]">إضافة دورة جديدة</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500">إجمالي الدورات</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $totalCourses }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500">إجمالي الطلاب</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $totalStudents }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500">إجمالي التسجيلات</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $totalEnrollments }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-500">متوسط الإكمال</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($averageCompletion, 1) }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-800">الفصول المباشرة</h2>
                <a href="{{ route('instructor.live-classes.index') }}" class="text-xs font-medium text-[#ed9134] hover:underline">الكل</a>
            </div>
            <div class="overflow-x-auto">
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

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-800">آخر التسجيلات</h2>
                <a href="{{ route('instructor.students.index') }}" class="text-xs font-medium text-[#ed9134] hover:underline">الكل</a>
            </div>
            <div class="overflow-x-auto">
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

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
                <h2 class="text-sm font-semibold text-slate-800">دوراتي</h2>
                <a href="{{ route('instructor.courses.index') }}" class="text-xs font-medium text-[#ed9134] hover:underline">الكل</a>
            </div>
            <div class="overflow-x-auto">
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
                                <a href="{{ route('instructor.courses.builder', $c) }}" class="text-xs font-medium text-[#ed9134] hover:underline">المنهج</a>
                                <span class="text-slate-300">·</span>
                                <a href="{{ route('instructor.courses.edit', $c) }}" class="text-xs text-slate-600 hover:underline">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-3 py-4 text-center text-xs text-slate-500">لا توجد دورات. <a href="{{ route('instructor.courses.create') }}" class="font-medium text-[#ed9134] hover:underline">إنشاء دورة</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
