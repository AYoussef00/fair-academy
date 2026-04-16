<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, Clock3 } from 'lucide-vue-next';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import SiteHeader from '@/components/SiteHeader.vue';

type CourseCard = {
    id: number;
    title: string;
    thumbnail?: string | null;
    price: number;
    category_name: string;
    instructor: string;
    duration?: string | null;
};

const props = withDefaults(
    defineProps<{
        courses?: CourseCard[];
    }>(),
    {
        courses: () => [],
    }
);
</script>

<template>
    <Head title="الدورات – أكاديمية فايرير للتدريب والتعليم" />
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <SiteHeader />

        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16">
                <p class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-1.5 text-xs font-semibold text-violet-700">
                    <BookOpen class="h-4 w-4" />
                    الكتالوج العام للدورات
                </p>
                <h1 class="mt-4 text-3xl font-extrabold sm:text-4xl">الدورات</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    تصفح الدورات المنشورة على المنصة واختر الدورة المناسبة لك.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">الدورات المتاحة</h2>
                <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ props.courses.length }} دورة
                </span>
            </div>

            <div
                v-if="props.courses.length === 0"
                class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500"
            >
                لا توجد دورات منشورة حالياً.
            </div>

            <div v-else class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="course in props.courses"
                    :key="course.id"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <Link :href="`/course/${course.id}`" class="block">
                        <ImageWithFallback
                            :src="course.thumbnail || undefined"
                            :alt="course.title"
                            class="h-52 w-full object-cover"
                        />

                        <div class="space-y-3 p-5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ course.category_name }}
                                </span>
                                <span class="text-xs text-slate-500">{{ course.instructor }}</span>
                            </div>

                            <h3 class="line-clamp-2 text-lg font-bold leading-snug text-slate-900">
                                {{ course.title }}
                            </h3>

                            <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                                <p class="text-lg font-extrabold text-[#ed9134]">
                                    {{ course.price > 0 ? `${course.price.toFixed(2)} ر.س` : 'مجاني' }}
                                </p>
                                <p v-if="course.duration" class="inline-flex items-center gap-1 text-xs text-slate-500">
                                    <Clock3 class="h-3.5 w-3.5" />
                                    {{ course.duration }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </article>
            </div>
        </section>
    </div>
</template>
