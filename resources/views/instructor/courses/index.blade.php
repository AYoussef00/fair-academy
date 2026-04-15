@extends('layouts.instructor')

@section('title', 'دوراتي')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">دوراتي</h1>
    <a href="{{ route('instructor.courses.create') }}" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">إنشاء دورة</a>
</div>
@if($courses->where('status', 'draft')->isNotEmpty())
    <p class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm text-amber-800">
        الدورات بحالة <strong>مسودة</strong> لا تظهر في الصفحة الرئيسية. انقر <strong>نشر</strong> بجانب أي دورة لإظهارها للزوار.
    </p>
@endif

<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4">العنوان</th>
                <th class="py-3 px-4">القسم</th>
                <th class="py-3 px-4">الحالة</th>
                <th class="py-3 px-4">المسجلون</th>
                <th class="py-3 px-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $c)
                <tr class="border-t border-gray-100">
                    <td class="py-3 px-4 font-medium">{{ $c->title }}</td>
                    <td class="py-3 px-4">{{ $c->category->name ?? '—' }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-sm {{ $c->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}" title="{{ $c->status === 'published' ? '' : 'الدورات المسودة لا تظهر في الصفحة الرئيسية' }}">{{ $c->status === 'published' ? 'منشور' : 'مسودة' }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $c->enrollments_count ?? 0 }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('instructor.courses.show', $c) }}" class="text-violet-600 text-sm me-2">عرض</a>
                        <a href="{{ route('instructor.courses.builder', $c) }}" class="text-violet-600 text-sm me-2">المنهج</a>
                        <form action="{{ route('instructor.courses.publish', $c) }}" method="POST" class="inline me-2">@csrf<button type="submit" class="text-violet-600 text-sm">{{ $c->status === 'published' ? 'إلغاء النشر' : 'نشر' }}</button></form>
                            <a href="{{ route('instructor.courses.edit', $c) }}" class="text-violet-600 text-sm me-2">تعديل</a>
                        <form action="{{ route('instructor.courses.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('حذف الدورة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        لا توجد دورات. <a href="{{ route('instructor.courses.create') }}" class="text-violet-600">إنشاء دورة جديدة</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($courses->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $courses->links() }}</div>
    @endif
</div>
@endsection
