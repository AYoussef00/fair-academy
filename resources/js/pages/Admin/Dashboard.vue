<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
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
    Users,
} from 'lucide-vue-next';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
];

const props = withDefaults(
    defineProps<{
        stats?: {
            usersCount: number;
            coursesCount: number;
            programsCount: number;
        };
    }>(),
    {
        stats: () => ({
            usersCount: 0,
            coursesCount: 0,
            programsCount: 0,
        }),
    }
);

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: true },
    { title: 'المستخدمين', href: '/admin/users', icon: UserRound, active: false },
    { title: 'الطلاب', href: '/admin/students', icon: GraduationCap, active: false },
    { title: 'الدورات', href: '/admin/courses', icon: BookOpen, active: false },
    { title: 'البرامج', href: '/admin/programs', icon: FolderOpen, active: false },
    { title: 'الكتب الرقمية', href: '/admin/digital-books', icon: BookText, active: false },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: false },
    { title: 'المدفوعات', href: '/admin/payments', icon: CreditCard, active: false },
    { title: 'التقارير', href: null, icon: BarChart3, active: false },
];
</script>

<template>
    <Head title="لوحة المدير" />

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
                    <div class="mb-6 rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">
                            لوحة التحكم
                        </h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            مرحبًا بك في لوحة إدارة المنصة.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-slate-500">إجمالي المستخدمين</p>
                                    <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                        {{ props.stats.usersCount }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                                    <Users :size="20" class="text-slate-600 dark:text-slate-300" />
                                </div>
                            </div>
                        </Card>
                        <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-slate-500">إجمالي الدورات</p>
                                    <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                        {{ props.stats.coursesCount }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                                    <BookOpen :size="20" class="text-slate-600 dark:text-slate-300" />
                                </div>
                            </div>
                        </Card>
                        <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-slate-500">إجمالي البرامج</p>
                                    <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                        {{ props.stats.programsCount }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                                    <FolderOpen :size="20" class="text-slate-600 dark:text-slate-300" />
                                </div>
                            </div>
                        </Card>
                    </div>

                    <Card class="mt-6 border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                            You're logged in!
                        </h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            هذه نسخة Dashboard بتصميم Laravel الكلاسيكي. يمكنك إدارة المستخدمين، الدورات، البرامج، والمجلة الإعلامية من القائمة الجانبية.
                        </p>
                    </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
