<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, Play, Video } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
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
            video_url?: string | null;
            lessons: Array<{
                id: number;
                title: string;
                type: string;
                duration?: number;
                order_number: number;
            }>;
        }>;
    };
    module: {
        id: number;
        title: string;
        description?: string | null;
        video_url?: string | null;
        order_number: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: props.course.title, href: `/course/${props.course.id}` },
    { title: props.module.title, href: '#' },
];

function moduleUrl(moduleId: number) {
    return `/course/${props.course.id}/module/${moduleId}`;
}
</script>

<template>
    <Head :title="`${module.title} – ${course.title}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-slate-50 dark:bg-slate-900">
            <div class="flex flex-col lg:flex-row lg:gap-6">
                <!-- Sidebar المنهج -->
                <aside class="order-1 w-full shrink-0 lg:w-80 lg:py-6">
                    <div class="sticky top-24 overflow-y-auto rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                            <BookOpen class="h-5 w-5 text-violet-600" />
                            المنهج
                        </h2>
                        <nav class="space-y-1">
                            <Link
                                v-for="m in course.modules"
                                :key="m.id"
                                :href="moduleUrl(m.id)"
                                class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm transition"
                                :class="m.id === module.id
                                    ? 'bg-violet-100 font-medium text-violet-800 dark:bg-violet-900/50 dark:text-violet-200'
                                    : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700'"
                            >
                                <Video class="h-4 w-4 shrink-0" />
                                <span class="line-clamp-2">{{ m.title }}</span>
                            </Link>
                        </nav>
                    </div>
                </aside>

                <!-- المحتوى الرئيسي - فيديو الوحدة -->
                <main class="order-2 min-w-0 flex-1 py-6">
                    <Card class="overflow-hidden p-0">
                        <div
                            v-if="module.video_url"
                            class="relative aspect-video w-full bg-black"
                        >
                            <iframe
                                :src="module.video_url"
                                title="فيديو الوحدة"
                                class="absolute inset-0 h-full w-full"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            />
                        </div>
                        <div
                            v-else
                            class="flex aspect-video w-full items-center justify-center bg-slate-200 dark:bg-slate-700"
                        >
                            <div class="text-center text-slate-500 dark:text-slate-400">
                                <Play class="mx-auto mb-2 h-16 w-16 opacity-50" />
                                <p class="text-sm">لا يتوفر فيديو لهذه الوحدة</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                                {{ module.title }}
                            </h1>
                            <p
                                v-if="module.description"
                                class="mt-4 whitespace-pre-wrap text-slate-600 dark:text-slate-400"
                            >
                                {{ module.description }}
                            </p>
                        </div>
                    </Card>

                    <div class="mt-6 flex items-center justify-between">
                        <Link
                            :href="`/course/${course.id}`"
                            class="text-sm font-medium text-violet-600 hover:text-violet-700"
                        >
                            ← العودة إلى الدورة
                        </Link>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
