<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    ArrowRight,
    BookOpen,
    CheckCircle2,
    Circle,
    Clock,
    Infinity,
    FileText,
    Play,
    Share2,
    Tag,
    User,
    Video,
} from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';
import ProgressBar from '@/components/ProgressBar.vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import { home, login, register, dashboard } from '@/routes';

const props = withDefaults(
    defineProps<{
        course: {
            id: number;
            title: string;
            description?: string | null;
            thumbnail?: string | null;
            duration?: string | null;
            price?: number;
            status?: string;
            updated_at?: string | null;
            category?: { id: number; name: string } | null;
            instructor?: { name: string } | null;
            modules: Array<{
                id: number;
                title: string;
                description?: string | null;
                order_number: number;
                lessons: Array<{
                    id: number;
                    title: string;
                    type: string;
                    duration?: number;
                    order_number: number;
                    is_free: boolean;
                }>;
            }>;
        };
        authUser?: { id: number; name: string } | null;
        enrolled?: boolean;
        progress?: number;
        completed_lessons?: number;
    }>(),
    { authUser: null, enrolled: false, progress: 0, completed_lessons: 0 }
);

const page = usePage();
const flashSuccess = computed(() => (page.props.flash as { success?: string })?.success);

function addToCart() {
    router.post(`/cart/add/${props.course.id}`);
}

const totalLessons = computed(() =>
    props.course.modules.reduce((sum, m) => sum + m.lessons.length, 0)
);

const firstLessonId = computed(() => {
    const modules = props.course.modules ?? [];
    const sortedModules = [...modules].sort(
        (a, b) => Number(a.order_number ?? 0) - Number(b.order_number ?? 0)
    );
    for (const mod of sortedModules) {
        const list = mod.lessons ?? [];
        if (list.length === 0) continue;
        const sortedLessons = [...list].sort(
            (a, b) => Number(a.order_number ?? 0) - Number(b.order_number ?? 0)
        );
        const first = sortedLessons[0];
        if (first?.id) return first.id;
    }
    return null;
});

/** أول وحدة (حسب ترتيب order_number) — تُستخدم عندما لا توجد دروس داخل الوحدات */
const firstModuleId = computed(() => {
    const modules = props.course.modules ?? [];
    if (modules.length === 0) return null;
    const sorted = [...modules].sort(
        (a, b) => Number(a.order_number ?? 0) - Number(b.order_number ?? 0)
    );
    return sorted[0]?.id ?? null;
});

const continueLearningHref = computed(() => {
    const cid = props.course?.id;
    if (cid == null) return dashboard();
    const lessonId = firstLessonId.value;
    if (lessonId != null) {
        return `/course/${cid}/lesson/${lessonId}`;
    }
    const moduleId = firstModuleId.value;
    if (moduleId != null) {
        return `/course/${cid}/module/${moduleId}`;
    }
    return dashboard();
});

const formattedDate = computed(() => {
    if (!props.course.updated_at) return null;
    try {
        const d = new Date(props.course.updated_at);
        return d.toLocaleDateString('ar-EG', {
            year: 'numeric',
            month: 'long',
        });
    } catch {
        return null;
    }
});

const descriptionBullets = computed(() => {
    const d = props.course.description;
    if (!d || !d.trim()) return [];
    return d
        .split(/\n+/)
        .map((s) => s.replace(/^[-•]\s*/, '').trim())
        .filter(Boolean);
});

const showLearnList = computed(
    () => descriptionBullets.value.length > 0 || props.course.description
);

function isLessonCompleted(moduleIndex: number, lessonIndex: number): boolean {
    let count = 0;
    for (let m = 0; m < props.course.modules.length; m++) {
        for (let l = 0; l < props.course.modules[m].lessons.length; l++) {
            if (m === moduleIndex && l === lessonIndex) {
                return count < props.completed_lessons;
            }
            count++;
        }
    }
    return false;
}

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
</script>

<template>
    <Head :title="`${course.title} – أكاديمية فايرير للتدريب والتعليم`" />
    <div class="min-h-screen bg-[#fafafa]">
        <SiteHeader />

        <!-- Dark header: breadcrumbs, title, tagline, meta -->
        <header class="border-b border-slate-200 bg-slate-900 px-4 py-8 text-white md:px-6">
            <div class="mx-auto max-w-6xl">
                <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-400">
                    <Link :href="home()" class="hover:text-white">الرئيسية</Link>
                    <span class="text-slate-500">/</span>
                    <template v-if="course.category">
                        <span>{{ course.category.name }}</span>
                        <span class="text-slate-500">/</span>
                    </template>
                    <span class="text-white">{{ course.title }}</span>
                </nav>
                <h1 class="text-2xl font-bold leading-tight md:text-3xl">
                    {{ course.title }}
                </h1>
                <p
                    v-if="course.description && !descriptionBullets.length"
                    class="mt-2 max-w-3xl text-slate-300"
                >
                    {{ course.description.slice(0, 120) }}{{ course.description.length > 120 ? '...' : '' }}
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-slate-400">
                    <span v-if="course.instructor">
                        تم الإنشاء بواسطة {{ course.instructor.name }}
                    </span>
                    <span v-if="formattedDate">
                        تاريخ آخر تحديث {{ formattedDate }}
                    </span>
                    <span>العربية</span>
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-6xl px-4 py-8 md:px-6">
            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- What you'll learn -->
                    <section v-if="showLearnList" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-xl font-bold text-slate-900">
                            <CheckCircle2 class="h-5 w-5 text-violet-600" />
                            ما ستتعلمه
                        </h2>
                        <ul
                            v-if="descriptionBullets.length > 0"
                            class="space-y-2 text-slate-700"
                        >
                            <li
                                v-for="(item, i) in descriptionBullets"
                                :key="i"
                                class="flex items-start gap-2"
                            >
                                <CheckCircle2 class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" />
                                <span>{{ item }}</span>
                            </li>
                        </ul>
                        <p
                            v-else-if="course.description"
                            class="whitespace-pre-wrap text-slate-700"
                        >
                            {{ course.description }}
                        </p>
                    </section>

                    <!-- Curriculum -->
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <h2 class="flex items-center gap-2 border-b border-slate-100 bg-slate-50/80 px-6 py-4 text-lg font-bold text-slate-900">
                            <BookOpen class="h-5 w-5 text-violet-600" />
                            محتوى الدورة
                        </h2>
                        <div class="divide-y divide-slate-100">
                            <div
                                v-for="(module, modIndex) in course.modules"
                                :key="module.id"
                                class="px-6 py-4"
                            >
                                <h3 class="font-semibold text-slate-900">
                                    {{ modIndex + 1 }}. {{ module.title }}
                                </h3>
                                <ul class="mt-2 space-y-1">
                                    <li
                                        v-for="(lesson, lessonIndex) in module.lessons"
                                        :key="lesson.id"
                                        class="flex items-center gap-3 text-sm"
                                    >
                                        <template v-if="enrolled">
                                            <Link
                                                :href="`/course/${course.id}/lesson/${lesson.id}`"
                                                class="flex flex-1 items-center gap-3 py-2 text-slate-700 hover:text-violet-600"
                                            >
                                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                                    <component :is="lessonIcon(lesson.type)" class="h-4 w-4" />
                                                </span>
                                                <span>{{ lesson.title }}</span>
                                                <span v-if="lesson.duration" class="text-slate-400">{{ lesson.duration }} د</span>
                                                <span
                                                    v-if="isLessonCompleted(modIndex, lessonIndex)"
                                                    class="text-emerald-500"
                                                >
                                                    <CheckCircle2 class="h-4 w-4" />
                                                </span>
                                                <span v-else class="text-slate-300">
                                                    <Circle class="h-4 w-4" />
                                                </span>
                                            </Link>
                                        </template>
                                        <template v-else>
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                                <component :is="lessonIcon(lesson.type)" class="h-4 w-4" />
                                            </span>
                                            <span class="flex-1 text-slate-600">{{ lesson.title }}</span>
                                            <span v-if="lesson.duration" class="text-slate-400">{{ lesson.duration }} د</span>
                                        </template>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Related topics -->
                    <section v-if="course.category" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-slate-900">
                            <Tag class="h-5 w-5 text-violet-600" />
                            استكشاف المواضيع ذات الصلة
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                :href="home()"
                                class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                            >
                                {{ course.category.name }}
                            </Link>
                        </div>
                    </section>
                </div>

                <!-- Sticky purchase box -->
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
                        <div class="overflow-hidden rounded-xl bg-slate-100">
                            <ImageWithFallback
                                :src="course.thumbnail ? (course.thumbnail.startsWith('http') ? course.thumbnail : `/storage/${course.thumbnail}`) : undefined"
                                :alt="course.title"
                                class="aspect-video w-full object-cover"
                            />
                            <div class="flex items-center justify-center gap-2 border-t border-slate-200 bg-slate-50 px-4 py-3">
                                <Play class="h-5 w-5 text-violet-600" />
                                <span class="text-sm font-medium text-slate-700">معاينة هذه الدورة</span>
                            </div>
                        </div>
                        <div
                            v-if="flashSuccess"
                            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
                        >
                            {{ flashSuccess }}
                        </div>
                        <p class="mt-4 text-sm text-slate-500">اشترِ دورات فردية</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">
                            {{ course.price != null && course.price > 0 ? `$${Number(course.price).toFixed(2)}` : 'مجاني' }}
                        </p>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600">
                            <li class="flex items-center gap-2">
                                <Clock class="h-4 w-4 text-violet-600" />
                                ضمان استرداد الأموال لمدة 30 يوماً
                            </li>
                            <li class="flex items-center gap-2">
                                <Infinity class="h-4 w-4 text-violet-600" />
                                الوصول الكامل مدى الحياة
                            </li>
                        </ul>
                        <div class="mt-6 space-y-3">
                            <template v-if="authUser">
                                <template v-if="enrolled">
                                    <div class="mb-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-slate-700">تقدّمك</span>
                                            <span class="text-slate-500">{{ completed_lessons }} / {{ totalLessons }} درس</span>
                                        </div>
                                        <ProgressBar :progress="progress" class="mt-1" />
                                    </div>
                                    <Link
                                        :href="continueLearningHref"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-600 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:opacity-95"
                                    >
                                        <Play class="h-4 w-4" />
                                        متابعة التعلّم
                                    </Link>
                                </template>
                                <template v-else>
                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-violet-600 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-violet-700"
                                        @click="addToCart"
                                    >
                                        إضافة إلى العربة
                                    </button>
                                    <Link
                                        :href="`/course/${course.id}`"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-slate-300 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                    >
                                        شراء الآن
                                    </Link>
                                </template>
                            </template>
                            <template v-else>
                                <Link
                                    :href="login()"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-violet-600 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-violet-700"
                                >
                                    إضافة إلى العربة
                                </Link>
                                <Link
                                    :href="login()"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-slate-300 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    شراء الآن
                                </Link>
                            </template>
                        </div>
                        <div class="mt-4 flex items-center gap-4 text-sm text-slate-500">
                            <button type="button" class="flex items-center gap-1 hover:text-slate-700">
                                <Share2 class="h-4 w-4" />
                                مشاركة
                            </button>
                            <span>تطبيق القسيمة</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>
