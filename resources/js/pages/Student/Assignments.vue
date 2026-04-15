<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Calendar,
    CheckCircle2,
    FileText,
    LayoutDashboard,
    MessageSquare,
    Receipt,
    UploadCloud,
    User,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const userName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';
const flash = page.props.flash as { success?: string | null };

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText, active: true },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText },
    { title: 'الحضور والغياب', href: '/student/attendance', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'الملف الشخصي', href: '/student/profile', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الواجبات والمشاريع', href: '/student/assignments' },
];

const props = defineProps<{
    assignments: Array<{
        id: number;
        course_title: string;
        title: string;
        description: string;
        due_in_text: string;
        due_date_human: string;
        status: 'pending' | 'submitted' | 'graded';
        grade: string | null;
        feedback: string | null;
        submitted: boolean;
    }>;
    userName: string;
}>();

function uploadAssignment(assignmentId: number, event: Event) {
    const target = event.target as HTMLInputElement | null;
    if (!target || !target.files?.length) return;

    const file = target.files[0];
    const formData = new FormData();
    formData.append('file', file);

    router.post(`/student/assignments/${assignmentId}/submit`, formData, {
        forceFormData: true,
        preserveScroll: true,
    });

    // إعادة ضبط حقل الملف حتى يمكن رفع نفس الملف مرة أخرى إذا لزم الأمر
    target.value = '';
}
</script>

<template>
    <Head title="الواجبات والمشاريع – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside
                    class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block"
                >
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
                    <div class="w-full px-6 py-6 space-y-6">
                        <!-- فلاش نجاح -->
                        <div
                            v-if="flash?.success"
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
                        >
                            {{ flash.success }}
                        </div>

                        <!-- العنوان -->
                        <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">📝 الواجبات والمشاريع</h1>
                                <p class="mt-1 text-sm text-slate-500">
                                    هنا تتابع كل الواجبات المطلوبة منك، ترفع الملفات، وتتابع درجاتك وملاحظات المدرب.
                                </p>
                            </div>
                            <div class="rounded-xl bg-white px-4 py-2 text-xs text-slate-500 shadow-sm dark:bg-slate-800 dark:text-slate-300">
                                <span class="font-semibold text-[#ed9134]">{{ assignments.length }}</span>
                                واجب/مشروع متاح حالياً
                            </div>
                        </div>

                        <!-- قائمة الواجبات -->
                        <div v-if="assignments.length > 0" class="space-y-4">
                            <Card
                                v-for="assignment in assignments"
                                :key="assignment.id"
                                class="overflow-hidden border border-slate-200 bg-white p-0 shadow-sm dark:border-slate-700 dark:bg-slate-800"
                            >
                                <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-start">
                                    <!-- معلومات الواجب -->
                                    <div class="min-w-0 flex-1 space-y-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                                                {{ assignment.course_title }}
                                            </span>
                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                                :class="assignment.status === 'graded'
                                                    ? 'bg-emerald-50 text-emerald-700'
                                                    : assignment.status === 'submitted'
                                                        ? 'bg-blue-50 text-blue-700'
                                                        : 'bg-amber-50 text-amber-700'"
                                            >
                                                {{
                                                    assignment.status === 'graded'
                                                        ? 'تم التصحيح'
                                                        : assignment.status === 'submitted'
                                                            ? 'تم التسليم – في انتظار التصحيح'
                                                            : 'لم يتم التسليم بعد'
                                                }}
                                            </span>
                                        </div>
                                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">
                                            {{ assignment.title }}
                                        </h2>
                                        <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-300">
                                            {{ assignment.description }}
                                        </p>

                                        <!-- درجة و ملاحظات بعد التصحيح -->
                                        <div
                                            v-if="assignment.status === 'graded' && assignment.grade"
                                            class="mt-2 flex flex-wrap items-center gap-2 text-xs"
                                        >
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                                                <CheckCircle2 class="h-3.5 w-3.5" />
                                                الدرجة: {{ assignment.grade }}
                                            </span>
                                            <span class="text-slate-500 dark:text-slate-300">
                                                {{ assignment.feedback }}
                                            </span>
                                        </div>
                                        <div
                                            v-else-if="assignment.feedback"
                                            class="mt-2 text-xs text-slate-500 dark:text-slate-300"
                                        >
                                            {{ assignment.feedback }}
                                        </div>
                                    </div>

                                    <!-- الديدلاين + رفع الواجب -->
                                    <div class="flex w-full flex-col items-stretch gap-2 sm:w-56 sm:items-end">
                                        <div class="flex items-center justify-between gap-2 sm:justify-end">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-200"
                                            >
                                                <Calendar class="h-3.5 w-3.5" />
                                                الموعد النهائي: {{ assignment.due_date_human }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-xs font-semibold"
                                            :class="assignment.due_in_text.includes('باقي') ? 'text-amber-600' : 'text-slate-500'"
                                        >
                                            {{ assignment.due_in_text }}
                                        </span>

                                        <!-- زر رفع الواجب -->
                                        <div class="mt-2 flex flex-col items-stretch gap-1">
                                            <label
                                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#ed9134] px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                                            >
                                                <UploadCloud class="h-4 w-4" />
                                                {{ assignment.submitted ? 'إعادة رفع الواجب' : 'رفع الواجب (PDF / Word / ZIP)' }}
                                                <input
                                                    type="file"
                                                    class="hidden"
                                                    accept=".pdf,.doc,.docx,.zip"
                                                    @change="uploadAssignment(assignment.id, $event)"
                                                />
                                            </label>
                                            <p
                                                v-if="assignment.submitted"
                                                class="text-[11px] text-slate-500 dark:text-slate-400"
                                            >
                                                تم رفع ملف لهذا الواجب سابقًا. يمكنك إعادة الرفع في أي وقت قبل الموعد النهائي.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </Card>
                        </div>

                        <!-- حالة فارغة -->
                        <div
                            v-else
                            class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center dark:border-slate-700 dark:bg-slate-800/40"
                        >
                            <FileText class="mb-4 h-10 w-10 text-slate-300" />
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">لا توجد واجبات أو مشاريع حالياً</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                عندما يضيف المدرب واجباً جديداً، سيظهر هنا مع التفاصيل وموعد التسليم.
                            </p>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>

