<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    AlertTriangle,
    BookOpen,
    Calendar,
    CheckCircle2,
    Clock,
    FileText,
    LayoutDashboard,
    MapPin,
    MessageSquare,
    Receipt,
    User,
    UserCheck,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const authUserName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';
const flash = page.props.flash as { success?: string | null };

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText },
    { title: 'الحضور والغياب', href: '/student/attendance', icon: Calendar, active: true },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'الملف الشخصي', href: '/student/profile', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الحضور والغياب', href: '/student/attendance' },
];

const props = defineProps<{
    records: Array<{
        id: number;
        date_human: string;
        course_title: string;
        session_title: string;
        status: 'present' | 'absent' | 'late';
    }>;
    attendancePercent: number;
}>();

const isAtRisk = props.attendancePercent < 80;
const isWarning = props.attendancePercent < 80 && props.attendancePercent >= 70;

function checkIn() {
    router.post('/student/attendance/check-in', {}, { preserveScroll: true });
}
</script>

<template>
    <Head title="الحضور والغياب – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside
                    class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block"
                >
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ authUserName }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">طالب</p>
                        </div>
                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="(item, index) in navItems" :key="index">
                                    <Link
                                        :href="item.href"
                                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150"
                                        :class="item.active
                                            ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100'"
                                    >
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                            :class="item.active ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'"
                                        >
                                            <component :is="item.icon" class="h-4 w-4" />
                                        </span>
                                        <span class="leading-tight">{{ item.title }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <!-- المحتوى الرئيسي -->
                <main class="min-w-0 flex-1">
                    <div class="w-full px-6 py-6 space-y-6">
                        <!-- فلاش نجاح -->
                        <div
                            v-if="flash?.success"
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/50 dark:bg-emerald-900/30 dark:text-emerald-100"
                        >
                            {{ flash.success }}
                        </div>

                        <!-- العنوان + زر سجّل حضورك -->
                        <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">✅ الحضور والغياب</h1>
                                <p class="mt-1 text-sm text-slate-500">
                                    هنا تتابع سجل حضورك في المحاضرات والجلسات المباشرة ونسبة حضورك في البرنامج.
                                </p>
                            </div>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#ed9134] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                                @click="checkIn"
                            >
                                <UserCheck class="h-4 w-4" />
                                سجّل حضورك الآن (أنت في القاعة)
                            </button>
                        </div>

                        <!-- نسبة الحضور + تحذير -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1.2fr),minmax(0,1.1fr)]">
                            <Card class="border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500">نسبة الحضور الكلية</p>
                                        <p class="mt-1 text-lg font-bold text-slate-900 dark:text-white">
                                            أنت حضرت
                                            <span class="text-[#ed9134]">{{ attendancePercent }}%</span>
                                            من المحاضرات
                                        </p>
                                        <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                            يُفضّل أن تكون نسبة حضورك أعلى من 80% لتجنّب الحرمان من الاختبار النهائي.
                                        </p>
                                    </div>
                                </div>
                            </Card>

                            <Card
                                v-if="isAtRisk"
                                class="border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 dark:border-amber-500/50 dark:bg-amber-900/30 dark:text-amber-100"
                            >
                                <div class="flex items-start gap-2">
                                    <AlertTriangle class="mt-0.5 h-4 w-4 shrink-0" />
                                    <div>
                                        <p class="text-sm font-bold">تنبيه هام بخصوص الحضور</p>
                                        <p class="mt-1">
                                            حضورك الحالي
                                            <span class="font-semibold">{{ attendancePercent }}%</span>
                                            –
                                            <span v-if="isWarning">قريب من الحد الأدنى المسموح به للحضور.</span>
                                            <span v-else>أقل من الحد الأدنى المسموح به وقد تتعرّض للحرمان من الاختبار.</span>
                                        </p>
                                    </div>
                                </div>
                            </Card>
                            <Card
                                v-else
                                class="border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-900/30 dark:text-emerald-100"
                            >
                                <div class="flex items-start gap-2">
                                    <CheckCircle2 class="mt-0.5 h-4 w-4 shrink-0" />
                                    <div>
                                        <p class="text-sm font-bold">حضورك ممتاز حتى الآن</p>
                                        <p class="mt-1">
                                            استمر بنفس الأداء لتحافظ على نسبة حضورك العالية وتستفيد من جميع المحاضرات والجلسات المباشرة.
                                        </p>
                                    </div>
                                </div>
                            </Card>
                        </div>

                        <!-- جدول الحضور والغياب -->
                        <Card class="overflow-hidden border border-slate-200 bg-white p-0 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-right dark:divide-slate-700">
                                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 dark:bg-slate-900/30">
                                        <tr>
                                            <th class="px-4 py-3">التاريخ</th>
                                            <th class="px-4 py-3">المقرر / البرنامج</th>
                                            <th class="px-4 py-3">المحاضرة / الجلسة</th>
                                            <th class="px-4 py-3">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs dark:divide-slate-700">
                                        <tr
                                            v-for="row in records"
                                            :key="row.id"
                                            class="hover:bg-slate-50/60 dark:hover:bg-slate-800/60"
                                        >
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">
                                                {{ row.date_human }}
                                            </td>
                                            <td class="max-w-xs px-4 py-3 text-slate-700 dark:text-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <BookOpen class="h-3.5 w-3.5 text-[#ed9134]" />
                                                    <span class="line-clamp-1">{{ row.course_title }}</span>
                                                </div>
                                            </td>
                                            <td class="max-w-xs px-4 py-3 text-slate-700 dark:text-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <Video class="h-3.5 w-3.5 text-slate-400" />
                                                    <span class="line-clamp-2">{{ row.session_title }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                                    :class="row.status === 'present'
                                                        ? 'bg-emerald-50 text-emerald-700'
                                                        : row.status === 'late'
                                                            ? 'bg-amber-50 text-amber-700'
                                                            : 'bg-red-50 text-red-700'"
                                                >
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full"
                                                        :class="row.status === 'present'
                                                            ? 'bg-emerald-500'
                                                            : row.status === 'late'
                                                                ? 'bg-amber-500'
                                                                : 'bg-red-500'"
                                                    />
                                                    <span v-if="row.status === 'present'">حاضر</span>
                                                    <span v-else-if="row.status === 'late'">متأخر</span>
                                                    <span v-else>غائب</span>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>

