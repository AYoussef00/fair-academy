<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, CheckCircle2, FileText, Play, Video } from 'lucide-vue-next';
import { computed } from 'vue';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { sanitizeRichHtml } from '@/lib/sanitizeHtml';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    course: {
        id: number;
        title: string;
        modules: Array<{
            id: number;
            title: string;
            order_number: number;
            lessons: Array<{
                id: number;
                title: string;
                type: string;
                duration?: number;
                order_number: number;
            }>;
        }>;
    };
    lesson: {
        id: number;
        title: string;
        type: string;
        video_url?: string | null;
        is_embedded_video?: boolean;
        content?: string | null;
        duration?: number | null;
    };
    completed_lesson_ids?: number[];
}>();

const completedIds = computed(() => new Set(props.completed_lesson_ids ?? []));
const safeLessonContent = computed(() => sanitizeRichHtml(props.lesson.content));

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: props.course.title, href: `/course/${props.course.id}` },
    { title: props.lesson.title, href: '#' },
];

function lessonIcon(type: string) {
    switch (type) {
        case 'video':
            return Video;
        case 'article':
            return FileText;
        default:
            return Play;
    }
}

function lessonUrl(lessonId: number) {
    return `/course/${props.course.id}/lesson/${lessonId}`;
}
</script>

<template>
    <Head :title="`${lesson.title} – ${course.title}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-slate-50 dark:bg-slate-900">
            <div class="flex flex-col lg:flex-row lg:gap-6">
                <!-- Sidebar المنهج (يظهر على اليمين في RTL) -->
                <aside class="order-1 w-full shrink-0 lg:w-80 lg:py-6">
                    <div class="sticky top-24 overflow-y-auto rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                            <BookOpen class="h-5 w-5 text-violet-600" />
                            المنهج
                        </h2>
                        <nav class="space-y-3">
                            <div
                                v-for="module in course.modules"
                                :key="module.id"
                                class="space-y-1"
                            >
                                <p class="px-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{ module.title }}
                                </p>
                                <ul class="space-y-0.5">
                                    <li v-for="l in module.lessons" :key="l.id">
                                        <Link
                                            :href="lessonUrl(l.id)"
                                            class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm transition"
                                            :class="l.id === lesson.id
                                                ? 'bg-violet-100 font-medium text-violet-800 dark:bg-violet-900/50 dark:text-violet-200'
                                                : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'"
                                        >
                                            <span
                                                v-if="completedIds.has(l.id)"
                                                class="flex h-5 w-5 shrink-0 items-center justify-center text-emerald-500"
                                                title="تمت المشاهدة"
                                            >
                                                <CheckCircle2 class="h-5 w-5" />
                                            </span>
                                            <component
                                                v-else
                                                :is="lessonIcon(l.type)"
                                                class="h-4 w-4 shrink-0"
                                            />
                                            <span class="line-clamp-2">{{ l.title }}</span>
                                            <span
                                                v-if="completedIds.has(l.id)"
                                                class="ms-auto shrink-0 text-xs font-medium text-emerald-600"
                                            >
                                                تمت المشاهدة
                                            </span>
                                            <span
                                                v-else-if="l.duration"
                                                class="ms-auto shrink-0 text-xs text-slate-400"
                                            >
                                                {{ l.duration }} د
                                            </span>
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </aside>

                <!-- المحتوى الرئيسي - فيديو الشرح (يظهر على اليسار في RTL) -->
                <main class="order-2 min-w-0 flex-1 py-6">
                    <Card class="overflow-hidden p-0">
                        <!-- Video -->
                        <div
                            v-if="lesson.video_url && lesson.is_embedded_video"
                            class="relative aspect-video w-full bg-black"
                        >
                            <iframe
                                :src="lesson.video_url"
                                title="فيديو الدرس"
                                class="absolute inset-0 h-full w-full"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            />
                        </div>
                        <div
                            v-else-if="lesson.video_url"
                            class="relative aspect-video w-full bg-black"
                        >
                            <video
                                :src="lesson.video_url"
                                controls
                                controlsList="nodownload"
                                playsinline
                                class="absolute inset-0 h-full w-full"
                            />
                        </div>
                        <div
                            v-else
                            class="flex aspect-video w-full items-center justify-center bg-slate-200 dark:bg-slate-700"
                        >
                            <div class="text-center text-slate-500 dark:text-slate-400">
                                <Play class="mx-auto mb-2 h-16 w-16 opacity-50" />
                                <p class="text-sm">لا يتوفر فيديو لهذا الدرس</p>
                            </div>
                        </div>

                        <!-- Lesson title & content -->
                        <div class="p-6">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                                {{ lesson.title }}
                            </h1>
                            <p
                                v-if="lesson.duration"
                                class="mt-1 text-sm text-slate-500"
                            >
                                المدة: {{ lesson.duration }} دقيقة
                            </p>
                            <div
                                v-if="lesson.content"
                                class="prose prose-slate mt-6 dark:prose-invert"
                                v-html="safeLessonContent"
                            />
                            <p
                                v-else-if="!lesson.video_url"
                                class="mt-6 text-slate-500"
                            >
                                محتوى هذا الدرس قيد الإعداد.
                            </p>
                        </div>
                    </Card>

                    <!-- Prev/Next (optional) -->
                    <div class="mt-6 flex items-center justify-between">
                        <Link
                            :href="`/course/${course.id}`"
                            class="text-sm font-medium text-violet-600 hover:text-violet-700"
                        >
                            ← العودة إلى الكورس
                        </Link>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
