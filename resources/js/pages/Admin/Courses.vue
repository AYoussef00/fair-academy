<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    BookText,
    CreditCard,
    FolderOpen,
    GraduationCap,
    LayoutDashboard,
    Newspaper,
    UserRound,
} from 'lucide-vue-next';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
    { title: 'الدورات', href: '/admin/courses' },
];

const props = withDefaults(
    defineProps<{
        courses?: Array<{
            id: number;
            title: string;
            program?: string | null;
            instructor?: string | null;
            price: number;
            duration?: string | null;
            status?: string | null;
            students_count: number;
            created_at?: string | null;
        }>;
    }>(),
    {
        courses: () => [],
    }
);

function toggleStatus(courseId: number) {
    router.patch(`/admin/courses/${courseId}/toggle-status`);
}

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: false },
    { title: 'المستخدمين', href: '/admin/users', icon: UserRound, active: false },
    { title: 'الطلاب', href: '/admin/students', icon: GraduationCap, active: false },
    { title: 'الدورات', href: '/admin/courses', icon: BookOpen, active: true },
    { title: 'البرامج', href: '/admin/programs', icon: FolderOpen, active: false },
    { title: 'الكتب الرقمية', href: '/admin/digital-books', icon: BookText, active: false },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: false },
    { title: 'المدفوعات', href: null, icon: CreditCard, active: false },
    { title: 'التقارير', href: null, icon: BarChart3, active: false },
];
</script>

<template>
    <Head title="الدورات - لوحة المدير" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-slate-100/70 dark:bg-slate-950">
            <div class="flex w-full">
                <aside class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block">
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Admin</p>
                            <p class="mt-0.5 text-xs text-slate-500">مدير النظام</p>
                        </div>
                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="item in sidebarItems" :key="item.title">
                                    <Link
                                        v-if="item.href"
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
                                    <button
                                        v-else
                                        type="button"
                                        class="flex w-full cursor-default items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-400"
                                    >
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800">
                                            <component :is="item.icon" class="h-4 w-4" />
                                        </span>
                                        <span class="leading-tight">{{ item.title }}</span>
                                        <span class="me-auto rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500 dark:bg-slate-800 dark:text-slate-400">قريباً</span>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <main class="min-w-0 flex-1">
                    <div class="w-full space-y-6 px-6 py-6">
                        <div class="rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">الدورات</h1>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">جدول بجميع الدورات في النظام وعدد الطلاب في كل دورة.</p>
                                </div>
                                <Link
                                    href="/admin/courses/create"
                                    class="inline-flex items-center justify-center rounded-xl bg-[#ed9134] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#d67d2a]"
                                >
                                    إضافة دورة جديدة
                                </Link>
                            </div>
                        </div>

                        <Card class="overflow-hidden border-slate-200 bg-white p-0 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div v-if="props.courses.length === 0" class="p-8 text-center text-sm text-slate-500">
                                لا توجد دورات حالياً.
                                <div class="mt-4">
                                    <Link
                                        href="/admin/courses/create"
                                        class="inline-flex items-center justify-center rounded-xl bg-[#ed9134] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#d67d2a]"
                                    >
                                        إضافة أول دورة
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                                    <thead class="bg-slate-50 dark:bg-slate-900/60">
                                        <tr>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">الدورة</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">البرنامج</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">المدرس</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">السعر</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">عدد الطلاب</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">الحالة</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        <tr v-for="course in props.courses" :key="course.id">
                                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ course.title }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ course.program || '—' }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ course.instructor || '—' }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ course.price.toFixed(2) }} ر.س</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ course.students_count }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                                {{ course.status || 'draft' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold text-white transition"
                                                    :class="course.status === 'published'
                                                        ? 'bg-rose-600 hover:bg-rose-700'
                                                        : 'bg-emerald-600 hover:bg-emerald-700'"
                                                    @click="toggleStatus(course.id)"
                                                >
                                                    {{ course.status === 'published' ? 'إيقاف' : 'نشر' }}
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
