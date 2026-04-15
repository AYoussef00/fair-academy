@extends('layouts.admin')

@section('title', 'الأقسام')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">الأقسام</h1>
    <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-slate-800 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">إضافة قسم</a>
</div>
<p class="mb-4 text-slate-600 text-sm">الأقسام (مثل: ذكاء اصطناعي، تطوير ويب) يضيفها المدير فقط، ويختارها المدرب عند إنشاء الدورة.</p>
<div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-slate-50">
            <tr>
                <th class="py-3 px-4 text-sm font-semibold text-slate-700">الاسم</th>
                <th class="py-3 px-4 text-sm font-semibold text-slate-700">المسار</th>
                <th class="py-3 px-4 text-sm font-semibold text-slate-700">عدد الدورات</th>
                <th class="py-3 px-4 text-sm font-semibold text-slate-700">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $c)
                <tr class="border-t border-slate-100">
                    <td class="py-3 px-4 font-medium">{{ $c->name }}</td>
                    <td class="py-3 px-4 text-slate-500 text-sm">{{ $c->slug }}</td>
                    <td class="py-3 px-4">{{ $c->courses_count ?? 0 }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.categories.edit', $c) }}" class="text-slate-600 text-sm hover:text-slate-900">تعديل</a>
                        <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline me-2" onsubmit="return confirm('حذف القسم؟');">@csrf @method('DELETE')<button type="submit" class="text-red-600 text-sm">حذف</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">لا توجد أقسام. <a href="{{ route('admin.categories.create') }}" class="text-slate-700 underline">إضافة قسم</a></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($categories->hasPages())<div class="p-4 border-t border-slate-100">{{ $categories->links() }}</div>@endif
</div>
@endsection
