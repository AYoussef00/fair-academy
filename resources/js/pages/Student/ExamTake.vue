<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Calendar,
    Clock,
    FileText,
    LayoutDashboard,
    ListChecks,
    MessageSquare,
    Receipt,
    Timer,
    User,
    Video,
} from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const pageUserName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText, active: true },
    { title: 'الحضور والغياب', href: '#', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'الملف الشخصي', href: '#', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const props = defineProps<{
    exam: {
        id: number;
        course_title: string;
        title: string;
        duration_minutes: number;
        total_questions: number;
        description: string;
        questions: Array<
            | {
                  id: number;
                  type: 'mcq';
                  text: string;
                  options: string[];
                  correct_index: number;
                  points: number;
              }
            | {
                  id: number;
                  type: 'true_false';
                  text: string;
                  correct_boolean: boolean;
                  points: number;
              }
            | {
                  id: number;
                  type: 'essay';
                  text: string;
                  max_words: number;
                  points: number;
              }
        >;
    };
    userName: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الاختبارات', href: '/student/exams' },
    { title: 'بدء الاختبار', href: '#' },
];

const remainingSeconds = ref(props.exam.duration_minutes * 60);
let timerId: number | undefined;

const formattedTime = computed(() => {
    const m = Math.floor(remainingSeconds.value / 60);
    const s = remainingSeconds.value % 60;
    return `${m} دقيقة ${s.toString().padStart(2, '0')} ثانية`;
});

const answers = reactive<Record<number, number | boolean | string>>({});
const submitted = ref(false);
const scorePercent = ref<number | null>(null);
const correctCount = ref(0);
const totalPoints = computed(() => props.exam.questions.reduce((sum, q: any) => sum + (q.points ?? 0), 0));

function autoSubmit() {
    if (submitted.value) return;
    submitExam();
}

function submitExam() {
    submitted.value = true;
    if (timerId) {
        clearInterval(timerId);
    }

    let earned = 0;
    let correct = 0;

    for (const q of props.exam.questions as any[]) {
        const ans = answers[q.id];
        if (q.type === 'mcq') {
            if (typeof ans === 'number' && ans === q.correct_index) {
                earned += q.points;
                correct++;
            }
        } else if (q.type === 'true_false') {
            if (typeof ans === 'boolean' && ans === q.correct_boolean) {
                earned += q.points;
                correct++;
            }
        } else if (q.type === 'essay') {
            // في الواقع يتم تصحيحها من المدرب، هنا فقط نحتسبها 0 مبدئياً
        }
    }

    correctCount.value = correct;
    scorePercent.value = totalPoints.value > 0 ? Math.round((earned / totalPoints.value) * 100) : 0;
}

onMounted(() => {
    timerId = window.setInterval(() => {
        if (remainingSeconds.value <= 0) {
            remainingSeconds.value = 0;
            autoSubmit();
        } else {
            remainingSeconds.value -= 1;
        }
    }, 1000);
});

onBeforeUnmount(() => {
    if (timerId) {
        clearInterval(timerId);
    }
});
</script>

<template>
    <Head :title="`بدء الاختبار – ${exam.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#111318]">
            <div class="flex w-full">
                <!-- سايدبار -->
                <aside
                    class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-[#1E2026] lg:block"
                >
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ pageUserName }}</p>
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

                <!-- محتوى الاختبار -->
                <main class="min-w-0 flex-1">
                    <div class="w-full px-4 py-6 space-y-4 sm:px-6">
                        <!-- هيدر الاختبار + المؤقت -->
                        <div class="grid gap-4 md:grid-cols-[2fr,minmax(0,1.1fr)]">
                            <Card class="border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-[#17181D]">
                                <div class="flex flex-col gap-2">
                                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-[11px] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        <BookOpen class="h-3.5 w-3.5 text-[#ed9134]" />
                                        {{ exam.course_title }}
                                    </div>
                                    <h1 class="text-lg font-bold text-slate-900 dark:text-white sm:text-xl">
                                        {{ exam.title }}
                                    </h1>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-300">
                                        {{ exam.description }}
                                    </p>
                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                                        <span class="inline-flex items-center gap-1">
                                            <ListChecks class="h-3.5 w-3.5 text-slate-400" />
                                            {{ exam.total_questions }} سؤال
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <Clock class="h-3.5 w-3.5 text-slate-400" />
                                            مدة الاختبار: {{ exam.duration_minutes }} دقيقة
                                        </span>
                                    </div>
                                </div>
                            </Card>

                            <!-- المؤقّت + ملخص النتيجة -->
                            <div class="space-y-3">
                                <Card class="border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-[#17181D]">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-200">
                                            <Timer class="h-4 w-4 text-[#ed9134]" />
                                            <span>المؤقت الزمني</span>
                                        </div>
                                        <span
                                            class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                        >
                                            {{ submitted ? 'تم إرسال الاختبار' : 'باقي' }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-base font-bold text-[#ed9134]">
                                        {{ formattedTime }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                        سيتم إرسال الإجابات تلقائياً عند انتهاء الوقت حتى لو لم تضغط على زر "إنهاء الاختبار".
                                    </p>
                                </Card>

                                <Card
                                    v-if="submitted"
                                    class="border border-emerald-200 bg-emerald-50/70 p-4 text-xs text-emerald-800 dark:border-emerald-600/60 dark:bg-emerald-900/40 dark:text-emerald-100"
                                >
                                    <p class="text-sm font-bold">تم إرسال الاختبار بنجاح ✅</p>
                                    <p class="mt-1">
                                        درجتك:
                                        <span class="font-semibold">
                                            {{ scorePercent }}%
                                        </span>
                                        (إجابات صحيحة في {{ correctCount }} من {{ exam.total_questions - 1 }} سؤال موضوعي)
                                    </p>
                                    <p class="mt-1 text-[11px]">
                                        الأسئلة المقالية يتم تقييمها يدوياً بواسطة المدرب، وقد تزيد درجتك النهائية بعد التصحيح.
                                    </p>
                                </Card>
                            </div>
                        </div>

                        <!-- الأسئلة -->
                        <Card class="border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-[#17181D]">
                            <div class="space-y-4">
                                <div
                                    v-for="(q, index) in exam.questions"
                                    :key="q.id"
                                    class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 text-sm dark:border-slate-800 dark:bg-[#1F222A]"
                                >
                                    <div class="mb-2 flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-xs font-bold text-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                                {{ index + 1 }}
                                            </span>
                                            <p class="font-semibold text-slate-800 dark:text-slate-100">
                                                {{ q.text }}
                                            </p>
                                        </div>
                                        <span class="text-[11px] text-slate-400">
                                            {{ (q as any).points }} درجة
                                        </span>
                                    </div>

                                    <!-- اختيار من متعدد -->
                                    <div v-if="q.type === 'mcq'" class="mt-2 space-y-2">
                                        <label
                                            v-for="(opt, optIndex) in q.options"
                                            :key="optIndex"
                                            class="flex cursor-pointer items-center gap-2 rounded-xl bg-white px-3 py-2 text-xs text-slate-700 shadow-sm ring-1 ring-slate-100 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-100 dark:ring-slate-800 dark:hover:bg-slate-800"
                                        >
                                            <input
                                                v-model="answers[q.id]"
                                                :value="optIndex"
                                                type="radio"
                                                class="h-3.5 w-3.5 text-[#ed9134] focus:ring-[#ed9134]"
                                                :disabled="submitted"
                                            />
                                            <span>{{ opt }}</span>
                                        </label>
                                    </div>

                                    <!-- صح / خطأ -->
                                    <div v-else-if="q.type === 'true_false'" class="mt-2 flex gap-3 text-xs">
                                        <label
                                            class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl bg-white px-3 py-2 text-slate-700 shadow-sm ring-1 ring-slate-100 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-100 dark:ring-slate-800 dark:hover:bg-slate-800"
                                        >
                                            <input
                                                v-model="answers[q.id]"
                                                type="radio"
                                                :value="true"
                                                class="h-3.5 w-3.5 text-[#ed9134] focus:ring-[#ed9134]"
                                                :disabled="submitted"
                                            />
                                            صح
                                        </label>
                                        <label
                                            class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl bg-white px-3 py-2 text-slate-700 shadow-sm ring-1 ring-slate-100 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-100 dark:ring-slate-800 dark:hover:bg-slate-800"
                                        >
                                            <input
                                                v-model="answers[q.id]"
                                                type="radio"
                                                :value="false"
                                                class="h-3.5 w-3.5 text-[#ed9134] focus:ring-[#ed9134]"
                                                :disabled="submitted"
                                            />
                                            خطأ
                                        </label>
                                    </div>

                                    <!-- سؤال مقالي -->
                                    <div v-else-if="q.type === 'essay'" class="mt-2 space-y-1 text-xs text-slate-500 dark:text-slate-300">
                                        <p>أجب بإيجاز (حد أقصى {{ q.max_words }} كلمة تقريباً):</p>
                                        <textarea
                                            v-model="answers[q.id] as string"
                                            class="mt-1 min-h-[90px] w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-[#ed9134] focus:outline-none focus:ring-1 focus:ring-[#ed9134] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500"
                                            :disabled="submitted"
                                        />
                                        <p class="text-[11px] text-slate-400">
                                            سيتم تصحيح هذا السؤال يدوياً من قبل المدرب.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- زر إنهاء الاختبار -->
                            <div class="mt-4 flex items-center justify-end gap-3">
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    تأكد من مراجعة إجاباتك قبل الإرسال، لا يمكنك التعديل بعد إنهاء الاختبار.
                                </p>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#ed9134] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                                    :disabled="submitted"
                                    @click="submitExam"
                                >
                                    إنهاء الاختبار
                                </button>
                            </div>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>

