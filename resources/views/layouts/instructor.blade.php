<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الأكاديمي') - {{ config('app.name') }}</title>
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
        html, body { overflow-x: hidden; width: 100%; box-sizing: border-box; }
        *, *::before, *::after { box-sizing: inherit; }
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: rgba(241, 245, 249, 0.7);
            color: rgb(15 23 42);
        }
        @media (min-width: 1024px) {
            .instructor-main {
                min-height: 100vh;
                margin-right: 15rem;
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-slate-100/70">
        <aside class="fixed top-0 right-0 z-30 hidden h-full w-60 shrink-0 border-l border-slate-200 bg-white shadow-sm lg:block">
            <div class="sticky top-0 flex h-full flex-col overflow-y-auto">
                <div class="border-b border-slate-100 px-5 py-5">
                    <p class="text-sm font-bold text-slate-900">Instructor</p>
                    <p class="mt-0.5 text-xs text-slate-500">لوحة الأكاديمي</p>
                </div>

                <nav class="flex-1 px-3 py-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150 {{ request()->routeIs('instructor.dashboard') ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('instructor.dashboard') ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500' }}">●</span>
                                <span class="leading-tight">لوحة التحكم</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instructor.courses.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150 {{ request()->routeIs('instructor.courses.*') ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('instructor.courses.*') ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500' }}">●</span>
                                <span class="leading-tight">دوراتي</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instructor.students.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150 {{ request()->routeIs('instructor.students.*') ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('instructor.students.*') ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500' }}">●</span>
                                <span class="leading-tight">الطلاب</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instructor.quizzes.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150 {{ request()->routeIs('instructor.quizzes.*') ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('instructor.quizzes.*') ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500' }}">●</span>
                                <span class="leading-tight">الاختبارات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instructor.live-classes.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150 {{ request()->routeIs('instructor.live-classes.*') ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('instructor.live-classes.*') ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500' }}">●</span>
                                <span class="leading-tight">الفصول المباشرة</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('instructor.analytics.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150 {{ request()->routeIs('instructor.analytics.*') ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('instructor.analytics.*') ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500' }}">●</span>
                                <span class="leading-tight">التحليلات</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="border-t border-slate-200 px-3 py-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">●</span>
                                <span class="leading-tight">العودة للموقع</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-right text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">●</span>
                                    <span class="leading-tight">تسجيل الخروج</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <main class="instructor-main min-w-0">
            <div class="min-w-0 px-4 py-5 sm:px-6 lg:px-6">
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
    </div>
</body>
</html>
