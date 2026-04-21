@extends('layouts.instructor')

@section('title', 'منهج الدورة - ' . $course->title)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">منهج الدورة</h1>
        <p class="text-slate-600 mt-1">{{ $course->title }}</p>
    </div>
    <a href="{{ route('instructor.courses.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">العودة للدورات</a>
</div>

{{-- إضافة موديول: عنوان + رفع الفيديو --}}
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-6">
    <h2 class="text-lg font-semibold text-slate-900 mb-4">إضافة موديول جديد</h2>
    <p class="text-slate-600 text-sm mb-4">كل موديول له عنوان والفيديو الخاص به (رفع ملف فيديو).</p>
    <form action="{{ route('instructor.modules.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="module_title" class="block text-sm font-semibold text-slate-700 mb-1">عنوان الموديول *</label>
            <input type="text" id="module_title" name="title" required placeholder="مثال: مقدمة عن الوحدة الأولى" value="{{ old('title') }}" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="module_video" class="block text-sm font-semibold text-slate-700 mb-1">الفيديو (رفع الملف)</label>
            <input type="file" id="module_video" name="video" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo" class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-medium file:text-violet-700 hover:file:bg-violet-100">
            <p class="mt-1 text-xs text-slate-500">MP4, WebM أو صيغ فيديو أخرى. الحد الأقصى 500 ميجا.</p>
            @error('video')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">إضافة الموديول</button>
    </form>
</div>

@foreach($course->modules as $module)
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm mb-6 overflow-hidden">
        <div class="flex justify-between items-start gap-4 border-b border-slate-100 bg-slate-50/80 px-5 py-4">
            <div class="min-w-0 flex-1">
                <h3 class="font-semibold text-slate-900">{{ $module->title }}</h3>
                @if($module->video_url)
                    @php $videoSrc = str_starts_with($module->video_url, 'http') ? $module->video_url : asset('storage/'.$module->video_url); @endphp
                    <p class="mt-1 text-sm text-slate-500">تم رفع الفيديو. <a href="{{ $videoSrc }}" target="_blank" rel="noopener" class="text-violet-600 hover:underline">عرض الفيديو</a></p>
                @else
                    <p class="mt-1 text-sm text-amber-600">لم يُرفع فيديو بعد</p>
                @endif
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <button type="button" onclick="document.getElementById('edit-module-{{ $module->id }}').classList.toggle('hidden');" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">تعديل</button>
                <form action="{{ route('instructor.modules.destroy', [$course, $module]) }}" method="POST" class="inline" onsubmit="return confirm('حذف هذا الموديول وجميع الدروس التابعة له؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50">حذف</button>
                </form>
            </div>
        </div>

        <div id="edit-module-{{ $module->id }}" class="hidden border-b border-slate-100 bg-white p-5">
            <form action="{{ route('instructor.modules.update', [$course, $module]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">عنوان الموديول</label>
                    <input type="text" name="title" value="{{ old('title', $module->title) }}" required class="block w-full rounded-xl border border-slate-300 px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">الفيديو (رفع ملف جديد لاستبدال الحالي)</label>
                    @if($module->video_url)
                        <p class="mb-1 text-xs text-slate-500">يوجد فيديو مرفوع حالياً. رفع ملف جديد سيستبدله.</p>
                    @endif
                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo" class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-medium file:text-violet-700 hover:file:bg-violet-100">
                    @error('video')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">حفظ التعديلات</button>
            </form>
        </div>

        {{-- دروس داخل الموديول (اختياري) --}}
        @if($module->lessons->isNotEmpty())
            <ul class="divide-y divide-slate-100">
                @foreach($module->lessons as $lesson)
                    @php
                        $isNewLesson = (int) session('new_lesson_id') === (int) $lesson->id;
                        $lessonVideoSrc = $lesson->video_url ? (str_starts_with($lesson->video_url, 'http') ? $lesson->video_url : asset('storage/'.$lesson->video_url)) : null;
                    @endphp
                    <li id="lesson-{{ $lesson->id }}" class="flex justify-between items-start gap-4 px-5 py-4 {{ $isNewLesson ? 'bg-emerald-50/70' : '' }}">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-medium text-slate-800">{{ $lesson->title }}</span>
                                <span class="text-sm text-slate-500">({{ $lesson->type }})</span>
                                @if($lesson->is_free)
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">مجاني</span>
                                @endif
                                @if($isNewLesson)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">تمت إضافته الآن</span>
                                @endif
                            </div>
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                @if($lesson->duration)
                                    <span>المدة: {{ $lesson->duration }} دقيقة</span>
                                @endif
                                @if($lesson->video_url)
                                    <span class="text-emerald-700">تم رفع فيديو لهذا الدرس</span>
                                    <a href="{{ $lessonVideoSrc }}" target="_blank" rel="noopener" class="font-medium text-violet-600 hover:underline">عرض الفيديو</a>
                                    @if(! str_starts_with($lesson->video_url, 'http'))
                                        <span class="truncate">الملف: {{ basename($lesson->video_url) }}</span>
                                    @endif
                                @else
                                    <span>لا يوجد فيديو مرفوع بعد</span>
                                @endif
                            </div>
                            @if($isNewLesson)
                                <p class="mt-2 text-xs font-medium text-emerald-700">يمكنك الآن إضافة درس فرعي آخر من النموذج بالأسفل.</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('instructor.lessons.edit', [$course, $lesson]) }}" class="text-sm text-violet-600 hover:underline">تعديل</a>
                            <form action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline" onsubmit="return confirm('حذف الدرس؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">حذف</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="border-t border-slate-100 bg-slate-50/50 p-4">
            <p class="text-sm text-slate-600 mb-1">إضافة درس فرعي (اختياري) داخل هذا الموديول:</p>
            <p class="mb-3 text-xs text-slate-500">بعد حفظ الدرس سيظهر مباشرة في القائمة أعلاه، ويمكنك استخدام نفس النموذج لإضافة درس فرعي آخر.</p>
            <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <div class="flex flex-wrap gap-2 items-end">
                    <input type="text" name="title" required placeholder="عنوان الدرس" class="rounded-lg border border-slate-300 px-3 py-2 min-w-[200px] flex-1">
                    <select name="type" class="rounded-lg border border-slate-300 px-3 py-2">
                        <option value="video">فيديو</option>
                        <option value="article">مقال</option>
                        <option value="live">مباشر</option>
                        <option value="file">ملف</option>
                    </select>
                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo" class="rounded-lg border border-slate-300 px-3 py-2 min-w-[220px] bg-white text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-medium file:text-violet-700 hover:file:bg-violet-100">
                    <input type="number" name="duration" placeholder="دقيقة" class="rounded-lg border border-slate-300 px-3 py-2 w-20">
                    <label class="flex items-center gap-1 text-sm text-slate-600"><input type="checkbox" name="is_free" value="1"> مجاني</label>
                    <button type="submit" class="rounded-lg bg-violet-600 text-white px-4 py-2 text-sm font-semibold hover:bg-violet-700">إضافة درس</button>
                </div>
                @error('title')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                @error('video')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </form>
        </div>
    </div>
@endforeach

@if($course->modules->isEmpty())
    <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 py-12 text-center text-slate-500">لا توجد موديولات بعد. استخدم النموذج أعلاه لإضافة موديول (عنوان + رفع الفيديو).</p>
@endif
@endsection
