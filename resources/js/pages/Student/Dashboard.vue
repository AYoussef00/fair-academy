<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    Bell,
    BookOpen,
    Calendar,
    CheckCircle2,
    Clock,
    FileText,
    FolderOpen,
    LayoutDashboard,
    MessageSquare,
    Newspaper,
    Receipt,
    TrendingUp,
    User,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import ProgressBar from '@/components/ProgressBar.vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const notifications = [
    { id: 1, text: 'تم رفع محاضرة جديدة في دورة Python', time: 'منذ ساعة', read: false },
    { id: 2, text: 'تذكير: موعد تسليم الواجب غداً', time: 'منذ 3 ساعات', read: false },
    { id: 3, text: 'تم نشر نتائج الاختبار النهائي', time: 'أمس', read: true },
];

const upcomingAssignments = [
    { id: 1, title: 'مشروع تحليل البيانات', course: 'Python للمبتدئين', due: 'غداً', urgent: true },
    { id: 2, title: 'واجب المصفوفات', course: 'هياكل البيانات', due: 'بعد يومين', urgent: false },
    { id: 3, title: 'تقرير UX', course: 'تصميم واجهات', due: 'بعد 5 أيام', urgent: false },
];

const gradeData = [
    { subject: 'Python', grade: 88 },
    { subject: 'هياكل البيانات', grade: 75 },
    { subject: 'تصميم واجهات', grade: 92 },
    { subject: 'قواعد بيانات', grade: 70 },
    { subject: 'شبكات', grade: 83 },
];

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText },
    { title: 'الحضور والغياب', href: '/student/attendance', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'المكتبة الرقمية', href: '/digital-library', icon: BookOpen },
    { title: 'المقالات', href: '/scientific-journal', icon: Newspaper },
    { title: 'الملف الشخصي', href: '/student/profile', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'لوحة التحكم',
        href: dashboard(),
    },
];

const page = usePage();
const userName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';

const props = withDefaults(
    defineProps<{
        programsCount?: number;
        coursesCount?: number;
        enrolledProgramsWithCourses?: Array<{
            program: { id: number; title: string; thumbnail?: string | null; slug: string };
            courses: Array<{
                id: number;
                title: string;
                thumbnail?: string | null;
                instructor: string;
                progress: number;
                total_lessons: number;
                completed_lessons: number;
            }>;
        }>;
        learningTimeHours?: number;
        averageProgress?: number;
    }>(),
    {
        programsCount: 0,
        coursesCount: 0,
        enrolledProgramsWithCourses: () => [],
        learningTimeHours: 0,
        averageProgress: 0,
    }
);

const recommendedCourses = [
    {
        id: 3,
        title: 'إتقان علم البيانات',
        instructor: 'إيميلي رودريغيز',
        rating: 4.8,
        students: 15420,
        thumbnail:
            'https://images.unsplash.com/photo-1666875753105-c63a6f3bdc86?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkYXRhJTIwc2NpZW5jZSUyMGFuYWx5dGljc3xlbnwxfHx8fDE3NzI4OTQ1MjN8MA&ixlib=rb-4.1.0&q=80&w=1080',
        price: '$89.99',
    },
    {
        id: 4,
        title: 'استراتيجية التسويق الرقمي',
        instructor: 'ديفيد بارك',
        rating: 4.9,
        students: 12350,
        thumbnail:
            'https://images.unsplash.com/photo-1661286178389-e067299f907e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYXJrZXRpbmclMjBkaWdpdGFsJTIwc3RyYXRlZ3l8ZW58MXx8fHwxNzcyODYxODA4fDA&ixlib=rb-4.1.0&q=80&w=1080',
        price: '$79.99',
    },
];

function goToCourse(id: number) {
    router.visit(`/course/${id}`);
}

function goToExplore() {
    router.visit('/explore');
}
</script>

<template>
    <Head title="لوحة التحكم – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block">
                    <div class="sticky top-20 overflow-y-auto">
                        <!-- معلومات المستخدم -->
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ userName }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">طالب</p>
                        </div>
                        <!-- القائمة -->
                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="(item, index) in navItems" :key="index">
                                    <Link
                                        :href="item.href"
                                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150"
                                        :class="index === 0
                                            ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100'"
                                    >
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                            :class="index === 0 ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'"
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

                        <!-- 1. ملخص سريع -->
                        <div class="rounded-2xl bg-gradient-to-l from-[#ed9134]/10 to-[#ed9134]/5 border border-[#ed9134]/20 p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h1 class="text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                                        أهلاً بك، {{ userName }}! 👋
                                    </h1>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                        عندك <span class="font-bold text-[#ed9134]">{{ upcomingAssignments.length }} واجبات قادمة</span> — واحد منهم يُسلَّم غداً!
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <div class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 shadow-sm dark:bg-slate-800">
                                        <BookOpen class="h-4 w-4 text-violet-600" />
                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ props.coursesCount }}</span>
                                        <span class="text-xs text-slate-500">دورة</span>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 shadow-sm dark:bg-slate-800">
                                        <FolderOpen class="h-4 w-4 text-fuchsia-600" />
                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ props.programsCount }}</span>
                                        <span class="text-xs text-slate-500">برنامج</span>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 shadow-sm dark:bg-slate-800">
                                        <TrendingUp class="h-4 w-4 text-emerald-500" />
                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ props.averageProgress }}%</span>
                                        <span class="text-xs text-slate-500">تقدّم</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2 + 3. المقررات الحالية + الإشعارات جنب بعض -->
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                            <!-- 2. المقررات الحالية (2/3) -->
                            <div class="lg:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white">📚 المقررات الحالية</h2>
                                    <span class="text-xs text-slate-400">{{ props.coursesCount }} دورة مسجّل</span>
                                </div>

                                <div v-if="props.enrolledProgramsWithCourses.length > 0" class="space-y-3">
                                    <template v-for="item in props.enrolledProgramsWithCourses" :key="item.program.id">
                                        <Card
                                            v-for="course in item.courses"
                                            :key="course.id"
                                            class="cursor-pointer overflow-hidden p-0 transition hover:shadow-md"
                                            @click="goToCourse(course.id)"
                                        >
                                            <div class="flex items-center gap-4 p-4">
                                                <ImageWithFallback
                                                    :src="course.thumbnail"
                                                    :alt="course.title"
                                                    class="h-16 w-24 shrink-0 rounded-lg object-cover"
                                                />
                                                <div class="min-w-0 flex-1">
                                                    <h3 class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ course.title }}</h3>
                                                    <p class="mt-0.5 text-xs text-slate-500">{{ course.instructor }}</p>
                                                    <div class="mt-2">
                                                        <ProgressBar :progress="course.progress" :show-label="false" />
                                                        <p class="mt-1 text-xs text-slate-400">{{ course.completed_lessons }}/{{ course.total_lessons }} درس · {{ course.progress }}%</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </Card>
                                    </template>
                                </div>
                                <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-10 text-center dark:border-slate-700 dark:bg-slate-800/30">
                                    <BookOpen class="mx-auto mb-2 h-8 w-8 text-slate-300" />
                                    <p class="text-sm text-slate-500">لا توجد دورات حتى الآن</p>
                                </div>
                            </div>

                            <!-- 3. الإشعارات (1/3) -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white">🔔 الإشعارات</h2>
                                    <span class="rounded-full bg-[#ed9134] px-2 py-0.5 text-xs font-bold text-white">
                                        {{ notifications.filter(n => !n.read).length }}
                                    </span>
                                </div>
                                <Card class="divide-y divide-slate-100 p-0 dark:divide-slate-700">
                                    <div
                                        v-for="notif in notifications"
                                        :key="notif.id"
                                        class="flex items-start gap-3 px-4 py-3.5"
                                        :class="!notif.read ? 'bg-[#ed9134]/5' : ''"
                                    >
                                        <span
                                            class="mt-1 flex h-2 w-2 shrink-0 rounded-full"
                                            :class="!notif.read ? 'bg-[#ed9134]' : 'bg-slate-200'"
                                        />
                                        <div class="min-w-0">
                                            <p class="text-xs leading-relaxed text-slate-700 dark:text-slate-300">{{ notif.text }}</p>
                                            <p class="mt-0.5 text-[11px] text-slate-400">{{ notif.time }}</p>
                                        </div>
                                    </div>
                                </Card>
                            </div>
                        </div>

                        <!-- 4. الدرجات + الواجبات القادمة جنب بعض -->
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                            <!-- الدرجات -->
                            <div class="space-y-4">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">📊 الدرجات والأداء</h2>
                                <Card class="p-5">
                                    <div class="space-y-3">
                                        <div
                                            v-for="item in gradeData"
                                            :key="item.subject"
                                            class="flex items-center gap-3"
                                        >
                                            <span class="w-28 shrink-0 text-xs font-medium text-slate-600 dark:text-slate-400 truncate">{{ item.subject }}</span>
                                            <div class="relative flex-1 h-5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                                <div
                                                    class="h-full rounded-full transition-all duration-500"
                                                    :style="{ width: item.grade + '%', backgroundColor: item.grade >= 85 ? '#30D158' : item.grade >= 70 ? '#ed9134' : '#FF3B30' }"
                                                />
                                            </div>
                                            <span
                                                class="w-10 shrink-0 text-right text-xs font-bold"
                                                :class="item.grade >= 85 ? 'text-emerald-600' : item.grade >= 70 ? 'text-[#ed9134]' : 'text-red-500'"
                                            >
                                                {{ item.grade }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex gap-4 border-t border-slate-100 pt-3 dark:border-slate-700">
                                        <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500" />ممتاز (85%+)</span>
                                        <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="inline-block h-2.5 w-2.5 rounded-full bg-[#ed9134]" />جيد (70%+)</span>
                                        <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500" />ضعيف</span>
                                    </div>
                                </Card>
                            </div>

                            <!-- الواجبات القادمة -->
                            <div class="space-y-4">
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">📝 الواجبات القادمة</h2>
                                <Card class="divide-y divide-slate-100 p-0 dark:divide-slate-700">
                                    <div
                                        v-for="assignment in upcomingAssignments"
                                        :key="assignment.id"
                                        class="flex items-start gap-3 px-4 py-3.5"
                                    >
                                        <span
                                            class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg"
                                            :class="assignment.urgent ? 'bg-red-100 text-red-500' : 'bg-slate-100 text-slate-400'"
                                        >
                                            <FileText class="h-4 w-4" />
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ assignment.title }}</p>
                                            <p class="mt-0.5 text-xs text-slate-500">{{ assignment.course }}</p>
                                        </div>
                                        <span
                                            class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold"
                                            :class="assignment.urgent ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-500'"
                                        >
                                            {{ assignment.due }}
                                        </span>
                                    </div>
                                </Card>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
