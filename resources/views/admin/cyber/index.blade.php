@extends('layouts.admin')

@section('title', 'لوحة الأمن السيبراني')

@push('styles')
<style>
    .cyber-page { --cyber-bg: #0f172a; --cyber-card: #1e293b; --cyber-border: #334155; --cyber-accent: #06b6d4; --cyber-success: #10b981; --cyber-warn: #f59e0b; --cyber-danger: #ef4444; }
    .cyber-hero { background: linear-gradient(135deg, var(--cyber-bg) 0%, #1e293b 50%, #0f172a 100%); border-bottom: 1px solid var(--cyber-border); }
    .cyber-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.06); transition: box-shadow 0.2s; }
    .cyber-card:hover { box-shadow: 0 4px 12px 0 rgb(0 0 0 / 0.08); }
    .cyber-table-wrap { border-radius: 0.75rem; overflow-x: auto; overflow-y: visible; border: 1px solid #e2e8f0; }
    .cyber-table { table-layout: fixed; width: 100%; }
    .cyber-table thead { background: #f8fafc; }
    .cyber-table th { font-weight: 600; color: #475569; padding: 0.75rem 1rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .cyber-table td { padding: 0.875rem 1rem; border-top: 1px solid #f1f5f9; line-height: 1.55; text-align: right; vertical-align: top; }
    .cyber-table tbody tr:hover { background: #f8fafc; }
    .cyber-table .cell-desc, .cyber-table .cell-fix { word-wrap: break-word; overflow-wrap: break-word; white-space: normal; }
    .cyber-table .cell-type { min-width: 140px; }
    .cyber-table .cell-file { font-size: 0.75rem; }
    .score-ring { transform: rotate(-90deg); }
    .badge-critical { background: #fef2f2; color: #b91c1c; }
    .badge-high { background: #fff1f2; color: #c2410c; }
    .badge-medium { background: #fffbeb; color: #b45309; }
    .badge-low { background: #f1f5f9; color: #475569; }
</style>
@endpush

@section('content')
    <div class="cyber-page space-y-8 pb-12">
        {{-- Hero header --}}
        <header class="cyber-hero -mx-4 -mt-8 rounded-2xl px-6 py-8 sm:-mx-6 sm:px-8 md:-mx-8 md:rounded-none md:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-700/80 text-cyan-400 shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">مركز عمليات الأمن السيبراني</h1>
                        <p class="mt-0.5 text-sm text-slate-400">SOC Dashboard — مراقبة الثغرات والأنشطة المشبوهة</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-slate-700/50 px-3 py-1.5 text-xs text-slate-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    نظام المراقبة مفعّل
                </div>
            </div>
        </header>

        {{-- KPI cards --}}
        <section class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="cyber-card flex flex-col p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">درجة الأمان</p>
                        <p class="mt-1 text-xs text-slate-500">بناءً على الثغرات والحماية المطبّقة</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-5">
                    <div class="relative h-20 w-20 flex-shrink-0">
                        <svg viewBox="0 0 36 36" class="h-20 w-20 score-ring">
                            <path class="text-slate-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2a16 16 0 1 1 0 32 16 16 0 0 1 0-32z"/>
                            @php $circumference = 2 * pi() * 16; $offset = $circumference - ($score / 100) * $circumference; @endphp
                            <path class="{{ $score >= 80 ? 'text-emerald-500' : ($score >= 60 ? 'text-amber-500' : 'text-red-500') }}" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" d="M18 2a16 16 0 1 1 0 32 16 16 0 0 1 0-32z"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-slate-800">{{ $score }}</span>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-900">
                            @if($score >= 80) مستوى الأمان جيد
                            @elseif($score >= 60) مستوى الأمان متوسط
                            @else تنبيه: مستوى الأمان منخفض
                            @endif
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">يُفضّل الحفاظ على الدرجة فوق 80</p>
                    </div>
                </div>
            </div>

            <div class="cyber-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">إحصائيات الثغرات</p>
                @php
                    $totalVulns = count($vulnerabilities);
                    $highVulns = collect($vulnerabilities)->whereIn('risk', ['High', 'Critical'])->count();
                @endphp
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">إجمالي الثغرات</span>
                        <span class="text-lg font-bold text-slate-900">{{ $totalVulns }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-sm text-slate-600">عالية / حرجة</span>
                        <span class="text-lg font-bold text-red-600">{{ $highVulns }}</span>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-500">عُدّل الثغرات الحرجة والعالية أولاً</p>
            </div>

            <div class="cyber-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">محاولات الهجوم</p>
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">إجمالي الأحداث</span>
                        <span class="text-lg font-bold text-slate-900">{{ $totalLogs }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-sm text-slate-600">عالية الخطورة (7 أيام)</span>
                        <span class="text-lg font-bold text-red-600">{{ $recentHighRisk }}</span>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-500">SQLi، XSS، فشل تسجيل الدخول، إلخ.</p>
            </div>
        </section>

        {{-- Security coverage --}}
        <section class="cyber-card overflow-hidden p-0">
            <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                <h2 class="text-base font-bold text-slate-900">تغطية الحماية (Security Coverage)</h2>
                <p class="mt-0.5 text-xs text-slate-500">الحماية المفعّلة وغير المفعّلة في التطبيق</p>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach(config('cyber.protections') as $key => $meta)
                    @php $enabled = $protections[$key] ?? false; @endphp
                    <div class="flex items-center gap-4 rounded-xl border p-4 {{ $enabled ? 'border-emerald-200 bg-emerald-50/80' : 'border-amber-200 bg-amber-50/80' }}">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full {{ $enabled ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }}">
                            @if($enabled)
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold {{ $enabled ? 'text-emerald-800' : 'text-amber-800' }}">{{ $meta['label'] }}</p>
                            <p class="text-xs {{ $enabled ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $enabled ? 'مفعّل' : 'غير مفعّل — يُنصح بتعزيزه' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Vulnerability report --}}
        <section class="cyber-card overflow-hidden p-0">
            <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                <h2 class="text-base font-bold text-slate-900">تقرير الثغرات (Vulnerability Report)</h2>
                <p class="mt-0.5 text-xs text-slate-500">قائمة الثغرات المكتشفة مع التوصيات</p>
            </div>
            <div class="cyber-table-wrap">
                <table class="cyber-table min-w-[800px] text-sm">
                    <colgroup>
                        <col style="width:14%">
                        <col style="width:8%">
                        <col style="width:16%">
                        <col style="width:10%">
                        <col style="width:26%">
                        <col style="width:26%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="cell-type">النوع</th>
                            <th>المستوى</th>
                            <th class="cell-file">الموقع</th>
                            <th>الحالة</th>
                            <th class="cell-desc">الشرح</th>
                            <th class="cell-fix">الإصلاح المقترح</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($vulnerabilities as $v)
                            <tr>
                                <td class="font-medium text-slate-800">{{ $v['type'] }}</td>
                                <td>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        @if($v['risk'] === 'Critical') badge-critical
                                        @elseif($v['risk'] === 'High') badge-high
                                        @elseif($v['risk'] === 'Medium') badge-medium
                                        @else badge-low @endif">
                                        {{ $v['risk'] }}
                                    </span>
                                </td>
                                <td class="cell-file font-mono text-slate-600">{{ $v['file'] }}</td>
                                <td>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ ($v['status'] ?? 'open') === 'open' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ ($v['status'] ?? 'open') === 'open' ? 'مفتوحة' : 'مغلقة' }}
                                    </span>
                                </td>
                                <td class="cell-desc text-slate-600">{{ $v['description'] }}</td>
                                <td class="cell-fix text-slate-600">{{ $v['recommendation'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Activity & Top IPs --}}
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1.6fr_1fr]">
            <div class="cyber-card overflow-hidden p-0">
                <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                    <h2 class="text-base font-bold text-slate-900">سجل الأنشطة المشبوهة (Cyber Activity)</h2>
                    <p class="mt-0.5 text-xs text-slate-500">آخر الأحداث المسجّلة من نظام المراقبة</p>
                </div>
                <div class="cyber-table-wrap">
                    <table class="cyber-table min-w-full text-right text-sm">
                        <thead>
                            <tr>
                                <th class="w-24">IP</th>
                                <th>المستخدم</th>
                                <th>نوع الحدث</th>
                                <th class="min-w-[140px]">المسار</th>
                                <th class="w-20">المستوى</th>
                                <th class="w-28">التوقيت</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td class="font-mono text-xs text-slate-700">{{ $log->ip_address ?? '—' }}</td>
                                    <td class="text-slate-700">{{ $log->user?->name ?? 'ضيف' }}</td>
                                    <td class="font-medium text-slate-800">{{ $log->event_type }}</td>
                                    <td class="font-mono text-xs text-slate-600 break-all" title="{{ $log->endpoint }}">{{ $log->endpoint ?? '—' }}</td>
                                    <td>
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                            @if($log->risk_level === 'critical') bg-red-100 text-red-700
                                            @elseif($log->risk_level === 'high') bg-red-50 text-red-600
                                            @elseif($log->risk_level === 'medium') bg-amber-50 text-amber-700
                                            @else bg-slate-100 text-slate-600 @endif">
                                            {{ ucfirst($log->risk_level) }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-slate-500">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-slate-400">لا توجد سجلات أنشطة مشبوهة بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cyber-card overflow-hidden p-0">
                <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                    <h2 class="text-base font-bold text-slate-900">أكثر عناوين IP نشاطاً</h2>
                    <p class="mt-0.5 text-xs text-slate-500">عناوين ذات محاولات متكررة</p>
                </div>
                <div class="max-h-[400px] space-y-2 overflow-y-auto p-4">
                    @forelse($topIps as $row)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 text-sm">
                            <div class="min-w-0 flex-1">
                                <p class="font-mono font-semibold text-slate-800">{{ $row->ip_address }}</p>
                                <p class="text-xs text-slate-500">آخر نشاط: {{ \Illuminate\Support\Carbon::parse($row->last_activity)->format('Y-m-d H:i') }}</p>
                                <p class="mt-0.5 text-xs text-slate-600">
                                    <span class="font-medium text-slate-700">النظام:</span> {{ $row->device_os ?? '—' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                {{ $row->attempts }} محاولة
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-slate-500">لا توجد بيانات عن عناوين IP مهاجمة حتى الآن.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
