<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    BookText,
    CheckCircle2,
    CreditCard,
    FolderOpen,
    GraduationCap,
    LayoutDashboard,
    Newspaper,
    UserRound,
    XCircle,
} from 'lucide-vue-next';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
    { title: 'الكتب الرقمية', href: '/admin/digital-books' },
];

const props = withDefaults(
    defineProps<{
        pendingBooks?: Array<{
            id: number;
            title: string;
            cover?: string | null;
            price: number;
            purchase_count: number;
            owner_name?: string | null;
            owner_email?: string | null;
            created_at?: string | null;
        }>;
        approvedBooks?: Array<{
            id: number;
            title: string;
            price: number;
            purchase_count: number;
            owner_name?: string | null;
            reviewer_name?: string | null;
            reviewed_at?: string | null;
        }>;
    }>(),
    {
        pendingBooks: () => [],
        approvedBooks: () => [],
    }
);

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: false },
    { title: 'المستخدمين', href: '/admin/users', icon: UserRound, active: false },
    { title: 'الطلاب', href: '/admin/students', icon: GraduationCap, active: false },
    { title: 'الدورات', href: '/admin/courses', icon: BookOpen, active: false },
    { title: 'البرامج', href: '/admin/programs', icon: FolderOpen, active: false },
    { title: 'الكتب الرقمية', href: '/admin/digital-books', icon: BookText, active: true },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: false },
    { title: 'المدفوعات', href: null, icon: CreditCard, active: false },
    { title: 'التقارير', href: null, icon: BarChart3, active: false },
];

function approveBook(id: number) {
    router.patch(`/admin/digital-books/${id}/approve`);
}

function rejectBook(id: number) {
    router.patch(`/admin/digital-books/${id}/reject`);
}
</script>

<template>
    <Head title="الكتب الرقمية - لوحة المدير" />

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
                                        <span class="me-auto rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            قريباً
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <main class="min-w-0 flex-1">
                    <div class="w-full space-y-6 px-6 py-6">
                        <div class="rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">مراجعة الكتب الرقمية</h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                وافق أو ارفض الكتب التي رفعها المستخدمون قبل نشرها في المكتبة.
                            </p>
                        </div>

                        <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">كتب قيد المراجعة</h2>
                            <div v-if="props.pendingBooks.length === 0" class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/50">
                                لا توجد كتب جديدة للمراجعة.
                            </div>
                            <div v-else class="space-y-4">
                                <article
                                    v-for="book in props.pendingBooks"
                                    :key="book.id"
                                    class="rounded-lg border border-slate-200 p-4 dark:border-slate-700"
                                >
                                    <div class="flex flex-col gap-4 sm:flex-row">
                                        <img
                                            :src="book.cover || 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80'"
                                            :alt="book.title"
                                            class="h-36 w-full rounded-lg object-cover sm:w-32"
                                        />
                                        <div class="flex-1">
                                            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ book.title }}</h3>
                                            <p class="mt-1 text-xs text-slate-500">{{ book.owner_name }} - {{ book.owner_email }}</p>
                                            <p class="mt-2 text-sm font-bold text-[#ed9134]">{{ book.price.toFixed(2) }} ر.س</p>
                                            <p class="mt-1 text-xs text-slate-500">عدد مرات الشراء: {{ book.purchase_count }}</p>
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center gap-1 rounded-md bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                                                    @click="approveBook(book.id)"
                                                >
                                                    <CheckCircle2 class="h-4 w-4" />
                                                    موافقة
                                                </button>
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center gap-1 rounded-md bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700"
                                                    @click="rejectBook(book.id)"
                                                >
                                                    <XCircle class="h-4 w-4" />
                                                    رفض
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </Card>

                        <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">آخر الكتب المعتمدة</h2>
                            <div v-if="props.approvedBooks.length === 0" class="text-sm text-slate-500 dark:text-slate-400">
                                لا توجد كتب معتمدة بعد.
                            </div>
                            <div v-else class="space-y-3">
                                <div
                                    v-for="book in props.approvedBooks"
                                    :key="book.id"
                                    class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700"
                                >
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ book.title }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ book.price.toFixed(2) }} ر.س - {{ book.owner_name }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">المبيعات: {{ book.purchase_count }}</p>
                                </div>
                            </div>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
