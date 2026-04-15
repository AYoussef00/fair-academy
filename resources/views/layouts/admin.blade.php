<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة المدير') - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Cairo', sans-serif; } </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen antialiased">
    <aside class="fixed top-0 right-0 z-30 h-full w-64 flex-shrink-0 overflow-y-auto border-l border-slate-200 bg-slate-800 text-white">
        <div class="p-5 border-b border-slate-700">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold">لوحة المدير</a>
        </div>
        <nav class="p-3 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm hover:bg-slate-700">الرئيسية</a>
            <a href="{{ route('admin.categories.index') }}" class="block rounded-lg px-3 py-2.5 text-sm hover:bg-slate-700">الأقسام</a>
            <a href="{{ route('cyber.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm hover:bg-slate-700 {{ request()->routeIs('cyber*') ? 'bg-cyan-600/30 text-cyan-300' : '' }}">الأمن السيبراني</a>
        </nav>
        <div class="border-t border-slate-700 p-3">
            <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm text-slate-300 hover:text-white">العودة</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">@csrf<button type="submit" class="w-full text-right text-sm text-slate-300 hover:text-white">تسجيل الخروج</button></form>
        </div>
    </aside>
    <main class="min-h-screen" style="margin-right: 16rem;">
        <div class="mx-auto px-4 py-8 sm:px-6 lg:px-8 {{ request()->routeIs('cyber*') ? 'max-w-6xl' : 'max-w-4xl' }}">
            @if(session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
    </main>
</body>
</html>
