@extends('layouts.instructor')

@section('title', 'الفصول المباشرة')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">الفصول المباشرة</h1>
    <a href="{{ route('instructor.live-classes.create') }}" class="rounded-lg bg-violet-600 text-white px-4 py-2 hover:bg-violet-700">جدولة فصل مباشر</a>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4">العنوان</th>
                <th class="py-3 px-4">الدورة</th>
                <th class="py-3 px-4">الوقت</th>
                <th class="py-3 px-4">المدة</th>
                <th class="py-3 px-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($liveClasses as $lc)
                <tr class="border-t border-gray-100">
                    <td class="py-3 px-4 font-medium">{{ $lc->title }}</td>
                    <td class="py-3 px-4">{{ $lc->course->title ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $lc->start_time?->format('Y-m-d H:i') ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $lc->duration ? $lc->duration . ' د' : '—' }}</td>
                    <td class="py-3 px-4">
                        @if($lc->meeting_url ?? $lc->zoom_meeting_id)
                            <a href="{{ $lc->meeting_url ?? '#' }}" target="_blank" class="text-violet-600 text-sm me-2">رابط الحصة</a>
                        @endif
                        <a href="{{ route('instructor.live-classes.edit', $lc) }}" class="text-violet-600 text-sm me-2">تعديل</a>
                        <form action="{{ route('instructor.live-classes.destroy', $lc) }}" method="POST" class="inline" onsubmit="return confirm('حذف الفصل المباشر؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">لا توجد فصول مباشرة. <a href="{{ route('instructor.live-classes.create') }}" class="text-violet-600">جدولة فصل</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($liveClasses->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $liveClasses->links() }}</div>
    @endif
</div>
@endsection
