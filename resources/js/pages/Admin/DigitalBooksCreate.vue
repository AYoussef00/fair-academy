<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    BookText,
    CreditCard,
    FolderOpen,
    GraduationCap,
    LayoutDashboard,
    Newspaper,
    Save,
    UserRound,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
    { title: 'الكتب الرقمية', href: '/admin/digital-books' },
    { title: 'إضافة كتاب', href: '/admin/digital-books/create' },
];

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: false },
    { title: 'المستخدمين', href: '/admin/users', icon: UserRound, active: false },
    { title: 'الطلاب', href: '/admin/students', icon: GraduationCap, active: false },
    { title: 'الدورات', href: '/admin/courses', icon: BookOpen, active: false },
    { title: 'البرامج', href: '/admin/programs', icon: FolderOpen, active: false },
    { title: 'الكتب الرقمية', href: '/admin/digital-books', icon: BookText, active: true },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: false },
    { title: 'المدفوعات', href: '/admin/payments', icon: CreditCard, active: false },
    { title: 'التقارير', href: null, icon: BarChart3, active: false },
];

const form = useForm({
    title: '',
    price: '0',
    cover: null as File | null,
});

function submit() {
    form.post('/admin/digital-books', {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="إضافة كتاب رقمي - لوحة المدير" />

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
                                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">إضافة كتاب رقمي</h1>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        أضف كتابًا جديدًا بعنوان وسعر وصورة غلاف؛ سيُنشر مباشرة في المكتبة الرقمية.
                                    </p>
                                </div>
                                <Link
                                    href="/admin/digital-books"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800"
                                >
                                    العودة للقائمة
                                </Link>
                            </div>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <form class="mx-auto max-w-xl space-y-5" @submit.prevent="submit">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">عنوان الكتاب *</label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        required
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-[#ed9134] focus:ring-2 focus:ring-[#ed9134]/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                                    >
                                    <p v-if="form.errors.title" class="mt-1 text-sm text-rose-600">{{ form.errors.title }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">السعر (ر.س) *</label>
                                    <input
                                        v-model="form.price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        required
                                        class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-[#ed9134] focus:ring-2 focus:ring-[#ed9134]/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                                    >
                                    <p v-if="form.errors.price" class="mt-1 text-sm text-rose-600">{{ form.errors.price }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">صورة الغلاف *</label>
                                    <input
                                        type="file"
                                        accept="image/jpeg,image/png,image/webp"
                                        class="mt-2 block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-medium file:text-slate-700 hover:file:bg-slate-200 dark:text-slate-400 dark:file:bg-slate-700 dark:file:text-slate-200"
                                        @change="form.cover = ($event.target as HTMLInputElement).files?.[0] ?? null"
                                    >
                                    <p v-if="form.errors.cover" class="mt-1 text-sm text-rose-600">{{ form.errors.cover }}</p>
                                </div>

                                <div class="flex flex-wrap gap-3 pt-2">
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#ed9134] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#d67d2a] disabled:opacity-60"
                                    >
                                        <Save class="h-4 w-4" />
                                        حفظ ونشر الكتاب
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
