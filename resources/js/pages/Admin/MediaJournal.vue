<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    BookOpen,
    CheckCircle2,
    FileText,
    FolderOpen,
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
    { title: 'المجلة الاعلامية', href: '/admin/media-journal' },
];

const props = withDefaults(
    defineProps<{
        pendingArticles?: Array<{
            id: number;
            title: string;
            researcher_name?: string | null;
            category: string;
            excerpt: string;
            keywords?: string | null;
            content: string;
            pdf_url?: string | null;
            author_name?: string | null;
            author_email?: string | null;
            created_at?: string | null;
        }>;
        approvedArticles?: Array<{
            id: number;
            title: string;
            researcher_name?: string | null;
            category: string;
            author_name?: string | null;
            reviewer_name?: string | null;
            reviewed_at?: string | null;
        }>;
    }>(),
    {
        pendingArticles: () => [],
        approvedArticles: () => [],
    }
);

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: false },
    { title: 'المستخدمين', href: null, icon: UserRound, active: false },
    { title: 'الدورات', href: null, icon: BookOpen, active: false },
    { title: 'البرامج', href: null, icon: FolderOpen, active: false },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: true },
];

function approveArticle(id: number) {
    router.patch(`/admin/media-journal/${id}/approve`);
}

function rejectArticle(id: number) {
    router.patch(`/admin/media-journal/${id}/reject`);
}
</script>

<template>
    <Head title="المجلة الاعلامية - لوحة المدير" />

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
                        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">مراجعة مقالات المجلة الإعلامية</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            المقالات لا تُنشر في الموقع إلا بعد موافقة الأدمن.
                        </p>
                    </div>

                    <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">مقالات قيد المراجعة</h2>

                        <div v-if="props.pendingArticles.length === 0" class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/50">
                            لا توجد مقالات جديدة للمراجعة.
                        </div>

                        <div v-else class="space-y-4">
                            <article
                                v-for="article in props.pendingArticles"
                                :key="article.id"
                                class="rounded-lg border border-slate-200 p-4 dark:border-slate-700"
                            >
                                <div class="mb-2 flex flex-wrap items-center gap-2 text-xs">
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 font-semibold text-amber-700">قيد المراجعة</span>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        {{ article.category }}
                                    </span>
                                    <span class="text-slate-500">{{ article.author_name }} - {{ article.author_email }}</span>
                                </div>
                                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ article.title }}</h3>
                                <p class="mt-1 text-xs text-slate-500">
                                    الباحث: {{ article.researcher_name || article.author_name || 'غير محدد' }}
                                </p>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ article.excerpt }}</p>
                                <p v-if="article.keywords" class="mt-2 text-xs text-slate-500">الكلمات المفتاحية: {{ article.keywords }}</p>
                                <p class="mt-3 line-clamp-4 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                    {{ article.content }}
                                </p>
                                <a
                                    v-if="article.pdf_url"
                                    :href="article.pdf_url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-violet-600 transition hover:text-violet-700"
                                >
                                    عرض ملف PDF
                                </a>
                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1 rounded-md bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                                        @click="approveArticle(article.id)"
                                    >
                                        <CheckCircle2 class="h-4 w-4" />
                                        موافقة ونشر
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1 rounded-md bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700"
                                        @click="rejectArticle(article.id)"
                                    >
                                        <FileText class="h-4 w-4" />
                                        رفض
                                    </button>
                                </div>
                            </article>
                        </div>
                    </Card>

                    <Card class="mt-6 border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">آخر المقالات المنشورة</h2>
                        <div v-if="props.approvedArticles.length === 0" class="text-sm text-slate-500 dark:text-slate-400">
                            لا توجد مقالات منشورة بعد.
                        </div>
                        <div v-else class="space-y-3">
                            <div
                                v-for="article in props.approvedArticles"
                                :key="article.id"
                                class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-slate-700"
                            >
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ article.title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ article.category }} - {{ article.researcher_name || article.author_name }}
                                    </p>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">بواسطة {{ article.reviewer_name }}</p>
                            </div>
                        </div>
                    </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
