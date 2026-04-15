<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة المدرب') - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        html { overflow-x: hidden; width: 100%; }
        body {
            font-family: 'Cairo', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            width: 100%;
            display: flex;
            flex-direction: row-reverse;
            min-height: 100vh;
        }
        html, body { box-sizing: border-box; }
        *, *::before, *::after { box-sizing: inherit; }
        /* السايدبار ثابت، والمحتوى يأخذ الباقي مع هامش يمين ولا يتجاوز (flex + min-width: 0) */
        @media (min-width: 1024px) {
            .instructor-main {
                flex: 1 1 0%;
                min-width: 0;
                min-height: 100vh;
                margin-right: 16rem;
                overflow-x: hidden;
            }
            .instructor-main .instructor-content {
                width: 100%;
                max-width: 100%;
                min-width: 0;
                margin-right: auto;
                margin-left: auto;
            }
        }
        .instructor-main { flex: 1; min-width: 0; overflow-x: hidden; }
        .instructor-main .instructor-content { width: 100%; max-width: 100%; min-width: 0; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen antialiased">
    {{-- السايدبار: مودرن واحترافي مع مسافة بين كل تاب --}}
    <aside class="fixed top-0 right-0 z-30 flex h-full w-64 flex-shrink-0 flex-col overflow-y-auto border-l border-slate-200 bg-white shadow-sm">
        {{-- اللوجو والعنوان --}}
        <div class="shrink-0 border-b border-slate-100 bg-slate-50/50 px-4 py-5">
            <a href="{{ route('instructor.dashboard') }}" class="block">
                <span class="text-sm font-bold leading-snug text-slate-900">أكاديمية فايرير للتدريب والتعليم</span>
            </a>
        </div>

        {{-- القائمة الرئيسية مع مسافات واضحة بين كل عنصر --}}
        <nav class="flex-1 px-3 py-5">
            <ul class="flex flex-col gap-2">
                <li>
                    <a href="{{ route('instructor.dashboard') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('instructor.dashboard') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>الرئيسية</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.courses.index') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('instructor.courses.*') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>دوراتي</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.students.index') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('instructor.students.*') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>الطلاب</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.quizzes.index') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('instructor.quizzes.*') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>الاختبارات</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.live-classes.index') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('instructor.live-classes.*') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>الفصول المباشرة</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.analytics.index') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('instructor.analytics.*') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>التحليلات</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- أسفل السايدبار --}}
        <div class="shrink-0 border-t border-slate-200 bg-slate-50/50 px-3 py-4">
            <ul class="flex flex-col gap-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-slate-100 hover:text-slate-700">
                        العودة للموقع
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl px-4 py-3 text-start text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-slate-100 hover:text-slate-700">
                            تسجيل الخروج
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    {{-- المحتوى الرئيسي: يملأ من اليسار حتى السايدبار، والمحتوى باين كله في المنتصف --}}
    <main class="instructor-main">
        <div class="instructor-content min-w-0 px-4 py-5 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</body>
</html>
