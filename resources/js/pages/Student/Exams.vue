<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Calendar,
    CheckCircle2,
    Clock,
    FileText,
    LayoutDashboard,
    ListChecks,
    MessageSquare,
    Radio,
    Receipt,
    User,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const userNameFromPage = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText, active: true },
    { title: 'الحضور والغياب', href: '/student/attendance', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'الملف الشخصي', href: '/student/profile', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الاختبارات', href: '/student/exams' },
];

const props = defineProps<{
    exams: Array<{
        id: number;
        course_title: string;
        title: string;
        status: 'upcoming' | 'live' | 'completed';
        date_human: string;
        time_human: string;
        duration_minutes: number;
        total_questions: number;
        score: string | null;
    }>;
    userName: string;
}>();

function goToExam(id: number) {
    router.visit(`/student/exams/${id}`);
}

function statusLabel(status: 'upcoming' | 'live' | 'completed') {
    if (status === 'live') return 'متاح الآن';
    if (status === 'upcoming') return 'قادِم';
    return 'منتهٍ';
}
</script>

<template>
    <Head title="الاختبارات – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside
                    class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block"
                >
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ userNameFromPage }}</p>
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
                        <!-- العنوان -->
                        <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">📊 التقييمات والاختبارات</h1>
                                <p class="mt-1 text-sm text-slate-500">
                                    هنا تتابع كل الاختبارات القادمة والمنتهية، وتبدأ الاختبار وترى درجتك مباشرة بعد الانتهاء.
                                </p>
                            </div>
                            <div class="rounded-xl bg-white px-4 py-2 text-xs text-slate-500 shadow-sm dark:bg-slate-800 dark:text-slate-300">
                                <span class="font-semibold text-[#ed9134]">{{ exams.length }}</span>
                                اختبار/كويز متاح لك
                            </div>
                        </div>

                        <!-- قائمة الاختبارات -->
                        <Card class="overflow-hidden border border-slate-200 bg-white p-0 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-right dark:divide-slate-700">
                                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 dark:bg-slate-900/30">
                                        <tr>
                                            <th class="px-4 py-3">المقرر</th>
                                            <th class="px-4 py-3">عنوان الاختبار</th>
                                            <th class="px-4 py-3">التاريخ</th>
                                            <th class="px-4 py-3">الوقت</th>
                                            <th class="px-4 py-3">الأسئلة</th>
                                            <th class="px-4 py-3">الحالة</th>
                                            <th class="px-4 py-3">الدرجة</th>
                                            <th class="px-4 py-3">الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs dark:divide-slate-700">
                                        <tr
                                            v-for="exam in exams"
                                            :key="exam.id"
                                            class="hover:bg-slate-50/60 dark:hover:bg-slate-800/60"
                                        >
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">
                                                {{ exam.course_title }}
                                            </td>
                                            <td class="max-w-xs px-4 py-3 text-slate-800 dark:text-slate-100">
                                                <div class="flex items-center gap-2">
                                                    <ListChecks class="h-3.5 w-3.5 text-[#ed9134]" />
                                                    <span class="line-clamp-2">{{ exam.title }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">
                                                {{ exam.date_human }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">
                                                <span class="inline-flex items-center gap-1">
                                                    <Clock class="h-3.5 w-3.5 text-slate-400" />
                                                    {{ exam.time_human }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">
                                                {{ exam.total_questions }} سؤال
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                                    :class="exam.status === 'live'
                                                        ? 'bg-emerald-50 text-emerald-700'
                                                        : exam.status === 'upcoming'
                                                            ? 'bg-amber-50 text-amber-700'
                                                            : 'bg-slate-100 text-slate-500'"
                                                >
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full"
                                                        :class="exam.status === 'live'
                                                            ? 'bg-emerald-500'
                                                            : exam.status === 'upcoming'
                                                                ? 'bg-amber-500'
                                                                : 'bg-slate-400'"
                                                    />
                                                    {{ statusLabel(exam.status) }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">
                                                <span v-if="exam.score" class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                                    <CheckCircle2 class="h-3.5 w-3.5" />
                                                    {{ exam.score }}
                                                </span>
                                                <span v-else class="text-[11px] text-slate-400">لم يُحدّد بعد</span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[11px] font-semibold text-white shadow-sm transition"
                                                    :class="exam.status === 'completed'
                                                        ? 'bg-slate-500 hover:bg-slate-600'
                                                        : 'bg-[#ed9134] hover:bg-[#d67d2a]'"
                                                    @click="goToExam(exam.id)"
                                                >
                                                    <Radio class="h-3.5 w-3.5" />
                                                    {{ exam.status === 'completed' ? 'عرض تفاصيل الاختبار' : 'ابدأ الاختبار' }}
                                                </button>
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

