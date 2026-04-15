<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    BookOpen,
    Award,
    Users,
    ArrowRight,
    Play,
    CheckCircle2,
    ChevronRight,
} from 'lucide-vue-next';
import { login, register, home, dashboard, courses } from '@/routes';
import SiteHeader from '@/components/SiteHeader.vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';

const props = defineProps<{
    categories?: Array<{ id: number; name: string }>;
    courses?: Array<{
        id: number;
        title: string;
        thumbnail: string | null;
        price: number;
        category_id: number | null;
        category_name: string;
        instructor: string;
        status?: string;
    }>;
    programs?: Array<{
        id: number;
        title: string;
        slug: string;
        thumbnail: string | null;
        price: number;
        duration: string | null;
        level: string | null;
    }>;
    canRegister?: boolean;
}>();

const page = usePage();
const authUser = (page.props.auth as { user?: unknown })?.user;

const categoriesList = computed(() => [
    { id: null as number | null, name: 'الكل' },
    ...(props.categories ?? []),
]);
const selectedCategoryId = ref<number | null>(null);
const coursesScrollRef = ref<HTMLElement | null>(null);
const coursesScrollRef2 = ref<HTMLElement | null>(null);

const filteredCourses = computed(() => {
    const list = props.courses ?? [];
    const catId = selectedCategoryId.value;
    if (catId == null) return list;
    return list.filter((c) => c.category_id === catId);
});

const programsList = computed(() => props.programs ?? []);

function scrollCoursesRight() {
    if (coursesScrollRef.value) {
        const step = 320;
        const direction = document.documentElement.dir === 'rtl' ? 1 : -1;
        coursesScrollRef.value.scrollBy({ left: step * direction, behavior: 'smooth' });
    }
}
function scrollCoursesRight2() {
    if (coursesScrollRef2.value) {
        const step = 320;
        const direction = document.documentElement.dir === 'rtl' ? 1 : -1;
        coursesScrollRef2.value.scrollBy({ left: step * direction, behavior: 'smooth' });
    }
}

const features = [
    {
        icon: BookOpen,
        title: 'تعلّم على راحتك',
        description:
            'ادخل للكورسات في أي وقت ومن أي مكان. درّب مع مواد متاحة مدى الحياة.',
    },
    {
        icon: Users,
        title: 'مدربون خبراء',
        description:
            'تعلّم من محترفين ومربّين يقدّمون خبرة حقيقية في كل درس.',
    },
    {
        icon: Award,
        title: 'شهادات ومعتمَدات',
        description:
            'احصل على شهادات معترف بها عند الإنجاز واعرض مهاراتك لأصحاب العمل.',
    },
];

const stats = [
    { value: '10,000+', label: 'طالب نشط' },
    { value: '500+', label: 'كورس' },
    { value: '98%', label: 'رضا' },
];

const benefits = [
    'دروس فيديو عالية الجودة',
    'اختبارات وواجبات',
    'تتبع التقدم',
    'منصة متوافقة مع الجوال',
    'دعم عندما تحتاجه',
];
</script>

<template>
    <Head title="أكاديمية فايرير للتدريب والتعليم – تعلّم بلا حدود" />

    <div class="min-h-screen bg-[#fafafa] text-slate-900">
        <SiteHeader />

        <!-- Hero -->
        <section class="relative overflow-hidden pt-28 pb-24 md:pt-36 md:pb-32">
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-20%,rgba(139,92,246,0.12),transparent)]"
            />
            <div
                class="absolute left-1/2 top-1/3 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-violet-400/15 blur-[120px]"
            />
            <div class="relative mx-auto max-w-6xl px-6 text-center">
                <h1
                    class="mx-auto max-w-4xl text-4xl font-extrabold leading-[1.15] tracking-tight text-slate-900 md:text-6xl lg:text-7xl"
                >
                    أتقن مهارات جديدة مع
                    <span style="color: #ed9134">
                        كورسات عالمية المستوى
                    </span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-slate-600 md:text-xl">
                    انضمّ إلى آلاف المتعلمين. ادخل إلى كورسات يقدّمها خبراء، واحصل على شهادات، وطوّر مسيرتك من أي مكان.
                </p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <Link
                        v-if="!authUser"
                        :href="register()"
                        class="group inline-flex items-center gap-2 rounded-xl px-8 py-4 text-base font-semibold text-white shadow-xl transition hover:opacity-95"
                    style="background-color: #ed9134; box-shadow: 0 20px 25px -5px rgb(237 145 52 / 0.3);"
                    >
                        ابدأ التعلّم مجاناً
                        <ArrowRight
                            class="h-5 w-5 transition group-hover:-translate-x-1 rtl:group-hover:translate-x-1"
                        />
                    </Link>
                    <Link
                        v-else
                        :href="dashboard()"
                        class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-violet-500/30 transition hover:shadow-violet-500/50"
                    >
                        لوحة التحكم
                        <ArrowRight
                            class="h-5 w-5 transition group-hover:-translate-x-1 rtl:group-hover:translate-x-1"
                        />
                    </Link>
                    <a
                        href="#how-it-works"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-8 py-4 text-base font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                    >
                        <Play class="h-5 w-5" />
                        اعرف كيف يعمل
                    </a>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="border-y border-slate-200 bg-white py-12">
            <div class="mx-auto max-w-6xl px-6">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div
                        v-for="(stat, i) in stats"
                        :key="i"
                        class="text-center"
                    >
                        <p class="text-3xl font-bold text-slate-900 md:text-4xl">
                            {{ stat.value }}
                        </p>
                        <p class="mt-1 text-sm font-medium text-slate-500">
                            {{ stat.label }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills to transform your career -->
        <section class="border-t border-slate-200 bg-white py-16 md:py-20">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">
                    دورات تُغيّر مسيرتك وحياتك
                </h2>
                <p class="mt-2 text-lg text-gray-600">
                    من المهارات الحرجة إلى المواضيع التقنية، أكاديمية فايرير للتدريب والتعليم تدعم تطوّرك المهني.
                </p>

                <!-- Category tabs (from DB) -->
                <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 border-b border-gray-200 pb-0">
                    <button
                        v-for="cat in categoriesList"
                        :key="cat.id"
                        type="button"
                        class="pb-4 text-sm font-medium transition hover:text-gray-900"
                        :class="selectedCategoryId === cat.id
                            ? 'border-b-2 border-gray-900 text-gray-900'
                            : 'text-gray-500'"
                        @click="selectedCategoryId = cat.id"
                    >
                        {{ cat.name }}
                    </button>
                </div>

                <!-- Course cards: horizontal scroll (from DB) -->
                <div class="relative mt-8">
                    <p
                        v-if="filteredCourses.length === 0"
                        class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-8 text-center text-slate-500"
                    >
                        لا توجد دورات حتى الآن.
                    </p>
                    <div
                        v-else
                        ref="coursesScrollRef"
                        class="flex gap-6 overflow-x-auto pb-4 scroll-smooth scrollbar-thin"
                        style="scrollbar-width: none; -ms-overflow-style: none"
                    >
                        <div
                            v-for="course in filteredCourses"
                            :key="course.id"
                            class="min-w-[280px] max-w-[280px] shrink-0 rounded-lg bg-white shadow-sm transition hover:shadow-md"
                        >
                            <Link :href="`/course/${course.id}`" class="block">
                                <ImageWithFallback
                                    :src="course.thumbnail ? `/storage/${course.thumbnail}` : undefined"
                                    :alt="course.title"
                                    class="h-40 w-full rounded-t-lg object-cover"
                                />
                                <div class="p-4">
                                    <div class="flex items-center gap-2">
                                        <h3 class="line-clamp-2 flex-1 text-base font-semibold text-gray-900">
                                            {{ course.title }}
                                        </h3>
                                        <span
                                            v-if="course.status === 'draft'"
                                            class="shrink-0 rounded bg-amber-100 px-1.5 py-0.5 text-xs font-medium text-amber-800"
                                        >
                                            مسودة
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ course.instructor }}
                                    </p>
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="font-bold text-gray-900">{{ course.price > 0 ? `$${course.price.toFixed(2)}` : 'مجاني' }}</span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <button
                        v-if="filteredCourses.length > 0"
                        type="button"
                        class="absolute -right-3 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white shadow-md transition hover:bg-gray-50"
                        aria-label="Scroll right"
                        @click="scrollCoursesRight"
                    >
                        <ChevronRight class="h-6 w-6 text-gray-700" />
                    </button>
                </div>

                <Link
                    :href="courses()"
                    class="mt-6 inline-flex items-center gap-1 text-sm font-medium text-violet-600 hover:text-violet-700"
                >
                    عرض كل الدورات
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
        </section>

        <!-- Programs section: برامج فقط -->
        <section class="border-t border-slate-200 bg-white py-16 md:py-20">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">
                    برامج تُغيّر مسيرتك وحياتك المهنية
                </h2>
                <p class="mt-2 text-lg text-gray-600">
                    من المهارات الحرجة إلى المواضيع التقنية، أكاديمية فايرير للتدريب والتعليم تدعم تطوّرك المهني.
                </p>

                <!-- Program cards: horizontal scroll (برامج من DB فقط) -->
                <div class="relative mt-8">
                    <p
                        v-if="programsList.length === 0"
                        class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-8 text-center text-slate-500"
                    >
                        لا توجد برامج حتى الآن.
                    </p>
                    <div
                        v-else
                        ref="coursesScrollRef2"
                        class="flex gap-6 overflow-x-auto pb-4 scroll-smooth scrollbar-thin"
                        style="scrollbar-width: none; -ms-overflow-style: none"
                    >
                        <div
                            v-for="program in programsList"
                            :key="'program-' + program.id"
                            class="min-w-[280px] max-w-[280px] shrink-0 rounded-lg bg-white shadow-sm transition hover:shadow-md"
                        >
                            <Link :href="home()" class="block">
                                <ImageWithFallback
                                    :src="program.thumbnail ? (program.thumbnail.startsWith('http') ? program.thumbnail : `/storage/${program.thumbnail}`) : undefined"
                                    :alt="program.title"
                                    class="h-40 w-full rounded-t-lg object-cover"
                                />
                                <div class="p-4">
                                    <h3 class="line-clamp-2 text-base font-semibold text-gray-900">
                                        {{ program.title }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        المدرب
                                    </p>
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="font-bold text-gray-900">{{ program.price > 0 ? `$${program.price.toFixed(2)}` : 'مجاني' }}</span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <button
                        v-if="programsList.length > 0"
                        type="button"
                        class="absolute -right-3 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white shadow-md transition hover:bg-gray-50"
                        aria-label="Scroll right"
                        @click="scrollCoursesRight2"
                    >
                        <ChevronRight class="h-6 w-6 text-gray-700" />
                    </button>
                </div>

                <Link
                    :href="home()"
                    class="mt-6 inline-flex items-center gap-1 text-sm font-medium text-[#ed9134] hover:text-[#d67d2a]"
                >
                    عرض كل البرامج
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
        </section>

        <!-- Features -->
        <section id="how-it-works" class="py-24 md:py-32">
            <div class="mx-auto max-w-6xl px-6">
                <p class="text-center text-sm font-semibold uppercase tracking-wider text-[#ed9134]">
                    لماذا أكاديمية فايرير للتدريب والتعليم
                </p>
                <h2 class="mt-3 text-center text-3xl font-bold text-slate-900 md:text-4xl">
                    كل ما تحتاجه للنجاح
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-center text-slate-600">
                    منصّة تدعم رحلة تعلّمك من البداية حتى النهاية.
                </p>
                <div class="mt-16 grid gap-8 md:grid-cols-3">
                    <div
                        v-for="(feature, i) in features"
                        :key="i"
                        class="group rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition hover:border-[#ed9134]/40 hover:shadow-md"
                    >
                        <div
                            class="mb-5 flex h-14 w-14 items-center justify-center rounded-xl bg-[#ed9134]/15 text-[#ed9134] transition group-hover:scale-105"
                        >
                            <component :is="feature.icon" class="h-7 w-7" />
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900">
                            {{ feature.title }}
                        </h3>
                        <p class="mt-3 leading-relaxed text-slate-600">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA + Benefits -->
        <section class="py-24 md:py-32">
            <div class="mx-auto max-w-6xl px-6">
                <div
                    class="relative overflow-hidden rounded-3xl border border-violet-100 bg-gradient-to-br from-violet-50 via-white to-fuchsia-50 p-12 md:p-16 shadow-lg"
                >
                    <div
                        class="absolute right-0 top-0 h-64 w-64 rounded-full bg-violet-200/40 blur-[100px]"
                    />
                    <div class="relative grid gap-12 md:grid-cols-2 md:items-center">
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 md:text-4xl">
                                مستعد لبدء التعلّم؟
                            </h2>
                            <p class="mt-4 text-lg text-slate-600">
                                انضم إلى أكاديمية فايرير للتدريب والتعليم اليوم واحصل على مئات الكورسات ومدربين خبراء وشهادات معتمدة.
                            </p>
                            <ul class="mt-6 space-y-3">
                                <li
                                    v-for="(benefit, i) in benefits"
                                    :key="i"
                                    class="flex items-center gap-3 text-slate-700"
                                >
                                    <CheckCircle2 class="h-5 w-5 shrink-0 text-emerald-500" />
                                    <span>{{ benefit }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="flex flex-col items-center justify-center md:items-end">
                            <template v-if="!authUser">
                                <Link
                                    :href="register()"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-600 px-8 py-4 text-base font-semibold text-white shadow-xl transition hover:shadow-violet-500/40"
                                >
                                    إنشاء حساب مجاني
                                    <ArrowRight class="h-5 w-5" />
                                </Link>
                                <p class="mt-4 text-sm text-slate-500">
                                    لا حاجة لبطاقة ائتمان.
                                </p>
                            </template>
                            <template v-else>
                                <Link
                                    :href="dashboard()"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-600 px-8 py-4 text-base font-semibold text-white shadow-xl transition hover:shadow-violet-500/40"
                                >
                                    فتح لوحة التحكم
                                    <ArrowRight class="h-5 w-5" />
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-6xl px-6 py-14">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Brand -->
                    <div class="lg:col-span-1">
                        <Link :href="home()" class="inline-block">
                            <img
                                src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                                alt="أكاديمية فايرير للتدريب والتعليم"
                                class="h-12 w-auto object-contain md:h-14"
                            />
                        </Link>
                        <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-600">
                            تعلّم بلا حدود. أتقن مهارات جديدة مع كورسات يقدّمها خبراء واحصل على شهادات معتمدة.
                        </p>
                    </div>

                    <!-- Platform -->
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-900">
                            المنصة
                        </h4>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <Link :href="authUser ? dashboard() : login()" class="text-sm text-slate-600 transition hover:text-violet-600">
                                    {{ authUser ? 'لوحة التحكم' : 'تسجيل الدخول' }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="register()" class="text-sm text-slate-600 transition hover:text-violet-600">
                                    إنشاء حساب
                                </Link>
                            </li>
                            <li>
                                <Link v-if="authUser" :href="dashboard()" class="text-sm text-slate-600 transition hover:text-violet-600">
                                    كورساتي
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-900">
                            الشركة
                        </h4>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">من نحن</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">اتصل بنا</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">الوظائف</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-900">
                            قانوني
                        </h4>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">سياسة الخصوصية</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">الشروط والأحكام</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">سياسة الكوكيز</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-8 md:flex-row">
                    <p class="text-sm text-slate-500">
                        © {{ new Date().getFullYear() }} أكاديمية فايرير للتدريب والتعليم. جميع الحقوق محفوظة.
                    </p>
                    <div class="flex gap-6 text-sm text-slate-500">
                        <Link :href="login()" class="transition hover:text-slate-700">تسجيل الدخول</Link>
                        <Link :href="register()" class="transition hover:text-slate-700">التسجيل</Link>
                        <Link v-if="authUser" :href="dashboard()" class="transition hover:text-slate-700">لوحة التحكم</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
