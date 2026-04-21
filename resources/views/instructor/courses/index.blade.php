@extends('layouts.instructor')

@section('title', 'دوراتي')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">إدارة الدورات</h1>
                <p class="mt-1 text-sm text-slate-500">إدارة كل الدورات الخاصة بك بنفس تنظيم لوحة المدير مع بقاء صلاحيات الأكاديمي فقط.</p>
            </div>
            <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#ed9134] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#d67d2a]">إضافة دورة جديدة</a>
        </div>
    </div>

    @if($courses->where('status', 'draft')->isNotEmpty())
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            الدورات بحالة <strong>مسودة</strong> لا تظهر في الصفحة الرئيسية. انقر <strong>نشر</strong> بجانب أي دورة لإظهارها للزوار.
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[880px] text-right">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200">
                        <th class="px-4 py-3 text-sm font-semibold text-slate-700">العنوان</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-700">القسم</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-700">الحالة</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-700">المسجلون</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $c)
                        <tr class="border-b border-slate-100 last:border-0">
                            <td class="px-4 py-4 font-medium text-slate-900">{{ $c->title }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $c->category->name ?? '—' }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $c->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}" title="{{ $c->status === 'published' ? '' : 'الدورات المسودة لا تظهر في الصفحة الرئيسية' }}">
                                    {{ $c->status === 'published' ? 'منشور' : 'مسودة' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm font-medium text-slate-700">{{ $c->enrollments_count ?? 0 }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('instructor.courses.show', $c) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">عرض</a>
                                    <a href="{{ route('instructor.courses.builder', $c) }}" class="rounded-lg border border-[#ed9134]/20 bg-[#ed9134]/10 px-3 py-1.5 text-xs font-medium text-[#ed9134] transition hover:bg-[#ed9134]/15">المنهج</a>
                                    <form action="{{ route('instructor.courses.publish', $c) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                                            {{ $c->status === 'published' ? 'إلغاء النشر' : 'نشر' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('instructor.courses.edit', $c) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50">تعديل</a>
                                    <form action="{{ route('instructor.courses.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('حذف الدورة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-100">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">
                                لا توجد دورات حتى الآن.
                                <a href="{{ route('instructor.courses.create') }}" class="font-semibold text-[#ed9134] hover:underline">إنشاء دورة جديدة</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="border-t border-slate-200 px-4 py-4">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
