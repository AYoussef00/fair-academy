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
    { title: 'الدورات', href: '/admin/courses' },
    { title: 'إضافة دورة جديدة', href: '/admin/courses/create' },
];

defineProps<{
    categories: Array<{ id: number; name: string }>;
    programs: Array<{ id: number; title: string }>;
    instructors: Array<{ id: number; name: string; email: string }>;
}>();

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

const form = useForm({
    program_id: '',
    category_id: '',
    instructor_id: '',
    title: '',
    description: '',
    thumbnail: null as File | null,
    price: '0',
    duration: '',
    status: 'draft',
});

function submit() {
    form.post('/admin/courses', {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="إضافة دورة جديدة - لوحة المدير" />

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
                                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">إضافة دورة جديدة</h1>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">أنشئ دورة جديدة وحدد القسم والمدرس والسعر وحالة النشر.</p>
                                </div>
                                <Link
                                    href="/admin/courses"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    العودة إلى الدورات
                                </Link>
                            </div>
                        </div>

                        <form
                            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900"
                            @submit.prevent="submit"
                        >
                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">عنوان الدورة</label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                    <p v-if="form.errors.title" class="mt-1 text-sm text-rose-600">{{ form.errors.title }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">القسم</label>
                                    <select
                                        v-model="form.category_id"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                        <option value="">اختر القسم</option>
                                        <option v-for="category in categories" :key="category.id" :value="String(category.id)">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.category_id" class="mt-1 text-sm text-rose-600">{{ form.errors.category_id }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">المدرس</label>
                                    <select
                                        v-model="form.instructor_id"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                        <option value="">اختر المدرس</option>
                                        <option v-for="instructor in instructors" :key="instructor.id" :value="String(instructor.id)">
                                            {{ instructor.name }} - {{ instructor.email }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.instructor_id" class="mt-1 text-sm text-rose-600">{{ form.errors.instructor_id }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">البرنامج</label>
                                    <select
                                        v-model="form.program_id"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                        <option value="">بدون برنامج</option>
                                        <option v-for="program in programs" :key="program.id" :value="String(program.id)">
                                            {{ program.title }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.program_id" class="mt-1 text-sm text-rose-600">{{ form.errors.program_id }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">السعر</label>
                                    <input
                                        v-model="form.price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                    <p v-if="form.errors.price" class="mt-1 text-sm text-rose-600">{{ form.errors.price }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">المدة</label>
                                    <input
                                        v-model="form.duration"
                                        type="text"
                                        placeholder="مثال: 10 ساعات"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                    <p v-if="form.errors.duration" class="mt-1 text-sm text-rose-600">{{ form.errors.duration }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">الحالة</label>
                                    <select
                                        v-model="form.status"
                                        class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                    >
                                        <option value="draft">مسودة</option>
                                        <option value="published">منشورة</option>
                                    </select>
                                    <p v-if="form.errors.status" class="mt-1 text-sm text-rose-600">{{ form.errors.status }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">صورة الغلاف</label>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        class="mt-2 block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-medium file:text-slate-700 hover:file:bg-slate-200"
                                        @change="form.thumbnail = ($event.target as HTMLInputElement).files?.[0] ?? null"
                                    >
                                    <p v-if="form.errors.thumbnail" class="mt-1 text-sm text-rose-600">{{ form.errors.thumbnail }}</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-slate-700">الوصف</label>
                                <textarea
                                    v-model="form.description"
                                    rows="6"
                                    class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-[#ed9134] focus:outline-none focus:ring-2 focus:ring-[#ed9134]/20"
                                />
                                <p v-if="form.errors.description" class="mt-1 text-sm text-rose-600">{{ form.errors.description }}</p>
                            </div>

                            <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#ed9134] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#d67d2a] disabled:cursor-not-allowed disabled:opacity-70"
                                >
                                    <Save class="h-4 w-4" />
                                    {{ form.processing ? 'جارٍ الحفظ...' : 'إنشاء الدورة' }}
                                </button>
                                <Link
                                    href="/admin/courses"
                                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                                >
                                    إلغاء
                                </Link>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
