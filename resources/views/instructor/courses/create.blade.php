@extends('layouts.instructor')

@section('title', 'إنشاء دورة')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">إنشاء دورة جديدة</h1>
    <p class="mt-1 text-slate-600">أدخل بيانات الدورة ثم أضف الوحدات والدروس من صفحة المنهج</p>
</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
        @csrf

        <div class="space-y-6">
            <div>
                <label for="category_id" class="block text-sm font-semibold text-slate-700">القسم <span class="text-red-500">*</span></label>
                <select id="category_id" name="category_id" required class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 sm:max-w-md">
                    <option value="">اختر القسم (مجال الدورة)</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @if($categories->isEmpty())
                    <p class="mt-1.5 text-sm text-amber-600">لا توجد أقسام متاحة. يرجى التواصل مع الإدارة لإضافة أقسام (مثل: ذكاء اصطناعي، تطوير ويب).</p>
                @endif
                @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">عنوان الدورة <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="مثال: تطوير واجهات الويب بـ Vue.js" class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700">الوصف</label>
                <textarea id="description" name="description" rows="5" placeholder="اشرح محتوى الدورة وأهدافها..." class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="thumbnail" class="block text-sm font-semibold text-slate-700">صورة الغلاف</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="mt-1.5 block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-medium file:text-violet-700 hover:file:bg-violet-100 sm:max-w-md">
                @error('thumbnail')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="price" class="block text-sm font-semibold text-slate-700">السعر</label>
                    <input type="number" id="price" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20">
                    @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="duration" class="block text-sm font-semibold text-slate-700">المدة</label>
                    <input type="text" id="duration" name="duration" value="{{ old('duration') }}" placeholder="مثال: 10 ساعات" class="mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 shadow-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20">
                    @error('duration')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
            <button type="submit" class="rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2">
                إنشاء الدورة
            </button>
            <a href="{{ route('instructor.courses.index') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
