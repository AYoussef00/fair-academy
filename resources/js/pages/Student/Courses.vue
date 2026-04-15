<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    BookOpen,
    Calendar,
    CheckCircle2,
    Clock,
    FileText,
    LayoutDashboard,
    MessageSquare,
    Play,
    Receipt,
    User,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import ProgressBar from '@/components/ProgressBar.vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import { dashboard } from '@/routes';
import { usePage } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

const page = usePage();
const userName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen, active: true },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText },
    { title: 'الحضور والغياب', href: '/student/attendance', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'الملف الشخصي', href: '/student/profile', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'دوراتي', href: '/student/courses' },
];

const props = defineProps<{
    courses: Array<{
        id: number;
        title: string;
        thumbnail?: string | null;
        instructor: string;
        category: string;
        progress: number;
        total_lessons: number;
        completed_lessons: number;
        enrolled_at?: string;
        first_lesson_id?: number | null;
    }>;
}>();

function continueCourse(course: typeof props.courses[0]) {
    if (course.first_lesson_id) {
        router.visit(`/course/${course.id}/lesson/${course.first_lesson_id}`);
    } else if (course.total_lessons === 0) {
        router.visit(`/course/${course.id}`);
    } else {
        router.visit(`/course/${course.id}`);
    }
}

function progressColor(p: number) {
    if (p >= 80) return 'text-emerald-600';
    if (p >= 40) return 'text-[#ed9134]';
    return 'text-slate-500';
}
</script>

<template>
    <Head title="دوراتي – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block">
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ userName }}</p>
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
                    <div class="w-full px-6 py-6">

                        <!-- Header -->
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">📚 دوراتي</h1>
                                <p class="mt-1 text-sm text-slate-500">
                                    <span class="font-semibold text-[#ed9134]">{{ courses.length }}</span>
                                    {{ courses.length === 1 ? 'دورة' : 'دورات' }} مسجّل فيها
                                </p>
                            </div>
                            <Link
                                href="/"
                                class="rounded-xl bg-[#ed9134] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                            >
                                + استعرض الدورات
                            </Link>
                        </div>

                        <!-- حالة فارغة -->
                        <div
                            v-if="courses.length === 0"
                            class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white py-20 dark:border-slate-700 dark:bg-slate-800/30"
                        >
                            <BookOpen class="mb-4 h-12 w-12 text-slate-300" />
                            <p class="text-base font-semibold text-slate-600 dark:text-slate-400">لا توجد دورات مسجّل فيها حتى الآن</p>
                            <p class="mt-1 text-sm text-slate-400">تصفّح الدورات المتاحة وابدأ رحلتك التعليمية</p>
                            <Link
                                href="/"
                                class="mt-5 rounded-xl bg-[#ed9134] px-5 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-[#d67d2a]"
                            >
                                استعرض الدورات
                            </Link>
                        </div>

                        <!-- قائمة الدورات -->
                        <div v-else class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            <Card
                                v-for="course in courses"
                                :key="course.id"
                                class="flex flex-col overflow-hidden p-0 transition hover:shadow-md"
                            >
                                <!-- الصورة -->
                                <div class="relative">
                                    <ImageWithFallback
                                        :src="course.thumbnail"
                                        :alt="course.title"
                                        class="h-40 w-full object-cover"
                                    />
                                    <!-- Badge الاكتمال -->
                                    <span
                                        v-if="course.progress >= 100"
                                        class="absolute left-3 top-3 flex items-center gap-1 rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-bold text-white shadow"
                                    >
                                        <CheckCircle2 class="h-3 w-3" />
                                        مكتملة
                                    </span>
                                </div>

                                <!-- التفاصيل -->
                                <div class="flex flex-1 flex-col p-4">
                                    <p v-if="course.category" class="mb-1 text-xs font-medium text-[#ed9134]">{{ course.category }}</p>
                                    <h3 class="line-clamp-2 text-sm font-bold text-slate-900 dark:text-white">{{ course.title }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">بواسطة {{ course.instructor }}</p>

                                    <!-- التقدّم -->
                                    <div class="mt-3">
                                        <div class="mb-1.5 flex items-center justify-between">
                                            <span class="text-xs text-slate-500">نسبة الإنجاز</span>
                                            <span
                                                class="rounded-full px-2 py-0.5 text-xs font-bold"
                                                :class="course.progress >= 100
                                                    ? 'bg-emerald-100 text-emerald-700'
                                                    : course.progress >= 50
                                                        ? 'bg-[#ed9134]/10 text-[#ed9134]'
                                                        : 'bg-slate-100 text-slate-600'"
                                            >
                                                {{ course.progress }}%
                                            </span>
                                        </div>
                                        <ProgressBar :progress="course.progress" :show-label="false" />
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ course.completed_lessons }} من {{ course.total_lessons }} درس مكتمل
                                        </p>
                                    </div>

                                    <!-- معلومات إضافية -->
                                    <div class="mt-3 flex items-center gap-3 text-xs text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <BookOpen class="h-3.5 w-3.5" />
                                            {{ course.total_lessons }} درس
                                        </span>
                                        <span v-if="course.enrolled_at" class="flex items-center gap-1">
                                            <Calendar class="h-3.5 w-3.5" />
                                            {{ course.enrolled_at }}
                                        </span>
                                    </div>

                                    <!-- زر أكمل الدورة -->
                                    <button
                                        type="button"
                                        class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-semibold text-white transition"
                                        :class="course.progress >= 100
                                            ? 'bg-emerald-500 hover:bg-emerald-600'
                                            : 'bg-[#ed9134] hover:bg-[#d67d2a]'"
                                        @click="continueCourse(course)"
                                    >
                                        <CheckCircle2 v-if="course.progress >= 100" class="h-4 w-4" />
                                        <Play v-else class="h-4 w-4" />
                                        {{ course.progress >= 100 ? 'تمت الدورة ✓' : 'أكمل الدورة' }}
                                    </button>
                                </div>
                            </Card>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
