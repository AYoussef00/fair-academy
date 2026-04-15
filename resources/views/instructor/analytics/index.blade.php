@extends('layouts.instructor')

@section('title', 'التحليلات')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">التحليلات</h1>
    <p class="text-gray-600 mt-1">نظرة عامة على أداء دوراتك وطلابك</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">الطلاب لكل دورة</h2>
        <ul class="space-y-2">
            @foreach($studentsPerCourse as $item)
                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="truncate me-2">{{ $item['course'] }}</span>
                    <span class="font-semibold text-violet-600">{{ $item['students'] }}</span>
                </li>
            @endforeach
        </ul>
        @if($studentsPerCourse->isEmpty())
            <p class="text-gray-500">لا توجد بيانات</p>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">متوسط إكمال الدورة %</h2>
        <ul class="space-y-2">
            @foreach($completionPerCourse as $item)
                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="truncate me-2">{{ $item['course'] }}</span>
                    <span class="font-semibold">{{ number_format($item['completion'], 1) }}%</span>
                </li>
            @endforeach
        </ul>
        @if(empty($completionPerCourse))
            <p class="text-gray-500">لا توجد بيانات</p>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">التسجيلات حسب الشهر (آخر 12 شهراً)</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[200px]">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="py-2 px-3">الشهر</th>
                    <th class="py-2 px-3">عدد التسجيلات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollmentsPerMonth as $month => $total)
                    <tr class="border-b border-gray-100">
                        <td class="py-2 px-3">{{ $month }}</td>
                        <td class="py-2 px-3 font-medium">{{ $total }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="py-4 text-gray-500 text-center">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-xl shadow border border-gray-100 p-6">
    <h2 class="text-lg font-semibold mb-4">أداء الاختبارات (ناجح / راسب)</h2>
    @if(count($quizPerformance) > 0)
        <ul class="space-y-2">
            @foreach($quizPerformance as $quizId => $perf)
                <li class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span>اختبار #{{ $quizId }}</span>
                    <span class="text-green-600">{{ $perf['passed'] }} ناجح</span>
                    <span class="text-red-600">{{ $perf['failed'] }} راسب</span>
                    <span class="text-gray-500">الإجمالي: {{ $perf['total'] }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">لا توجد محاولات اختبار بعد</p>
    @endif
</div>
@endsection
