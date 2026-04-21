<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Award,
    Users,
    ArrowRight,
    CheckCircle2,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import { courses as coursesRoute, dashboard, home, login, register } from '@/routes';

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
const flash = computed(() => (page.props.flash as { success?: string | null; error?: string | null }) ?? {});
const showSuccessPopup = ref(Boolean(flash.value.success));

const categoriesList = computed(() => [
    { id: null as number | null, name: 'الكل' },
    ...(props.categories ?? []),
]);
const selectedCategoryId = ref<number | null>(null);

const filteredCourses = computed(() => {
    const list = props.courses ?? [];
    const catId = selectedCategoryId.value;
    if (catId == null) return list;
    return list.filter((c) => c.category_id === catId);
});

const programsList = computed(() => props.programs ?? []);

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

    <div class="min-h-screen bg-[linear-gradient(180deg,#f8fafc_0%,#ffffff_22%,#f8fafc_100%)] text-slate-900">
        <SiteHeader />

        <div
            v-if="showSuccessPopup && flash.success"
            class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/45 p-4 backdrop-blur-sm"
        >
            <div class="w-full max-w-md rounded-[2rem] bg-white p-7 text-center shadow-2xl">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <CheckCircle2 class="h-9 w-9" />
                </div>
                <h2 class="mt-5 text-2xl font-bold text-slate-950">تم الدفع بنجاح</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    {{ flash.success }}
                </p>
                <button
                    type="button"
                    class="mt-6 inline-flex items-center justify-center rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                    @click="showSuccessPopup = false"
                >
                    إغلاق
                </button>
            </div>
        </div>

        <!-- Hero -->
        <section class="relative overflow-hidden pb-18 pt-16 sm:pt-20 md:pb-28 md:pt-24">
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_75%_55%_at_50%_-10%,rgba(237,145,52,0.12),transparent)]"
            />
            <div
                class="absolute left-1/2 top-1/3 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-slate-200/70 blur-[130px]"
            />
            <div class="relative mx-auto max-w-6xl px-4 text-center sm:px-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/80 px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-[#ed9134]" />
                    تجربة تعلّم أكثر هدوءًا واحترافية
                </div>
                <h1
                    class="mx-auto mt-6 max-w-5xl text-4xl font-extrabold leading-[1.08] tracking-[-0.03em] text-slate-950 sm:text-5xl md:text-6xl lg:text-[5.2rem]"
                >
                    أتقن مهارات جديدة مع
                    <span class="text-[#ed9134]">
                        كورسات عالمية المستوى
                    </span>
                </h1>
                <p class="mx-auto mt-6 max-w-3xl text-base leading-8 text-slate-600 sm:text-lg md:text-[1.35rem] md:leading-9">
                    انضمّ إلى آلاف المتعلمين. ادخل إلى كورسات يقدّمها خبراء، واحصل على شهادات، وطوّر مسيرتك من أي مكان.
                </p>
                <div class="mt-10 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center sm:gap-4">
                    <Link
                        v-if="!authUser"
                        :href="register()"
                        class="group inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-slate-950 px-6 py-4 text-base font-semibold text-white shadow-[0_20px_50px_rgba(15,23,42,0.18)] transition hover:bg-slate-800 sm:w-auto sm:px-8"
                    >
                        ابدأ التعلّم مجاناً
                        <ArrowRight
                            class="h-5 w-5 transition group-hover:-translate-x-1 rtl:group-hover:translate-x-1"
                        />
                    </Link>
                    <Link
                        v-else
                        :href="dashboard()"
                        class="group inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-slate-950 px-6 py-4 text-base font-semibold text-white shadow-[0_20px_50px_rgba(15,23,42,0.18)] transition hover:bg-slate-800 sm:w-auto sm:px-8"
                    >
                        لوحة التحكم
                        <ArrowRight
                            class="h-5 w-5 transition group-hover:-translate-x-1 rtl:group-hover:translate-x-1"
                        />
                    </Link>
                </div>

                <div class="mx-auto mt-12 grid max-w-4xl gap-3 sm:grid-cols-3">
                    <div class="rounded-[1.75rem] border border-white/80 bg-white/75 px-5 py-4 text-start shadow-sm backdrop-blur">
                        <p class="text-xs font-semibold text-slate-500">تعليم مرن</p>
                        <p class="mt-2 text-sm leading-7 text-slate-700">تعلم من أي مكان وارجع للمحتوى متى شئت.</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-white/80 bg-white/75 px-5 py-4 text-start shadow-sm backdrop-blur">
                        <p class="text-xs font-semibold text-slate-500">خبرة عملية</p>
                        <p class="mt-2 text-sm leading-7 text-slate-700">دورات وبرامج مصممة لتطوير مهاراتك فعليًا.</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-white/80 bg-white/75 px-5 py-4 text-start shadow-sm backdrop-blur">
                        <p class="text-xs font-semibold text-slate-500">شهادات معتمدة</p>
                        <p class="mt-2 text-sm leading-7 text-slate-700">وثّق إنجازك وشارك مسيرتك المهنية بثقة.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-6 sm:py-8">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-5">
                    <div
                        v-for="(stat, i) in stats"
                        :key="i"
                        class="rounded-[2rem] border border-white/80 bg-white/85 px-6 py-7 text-center shadow-sm backdrop-blur"
                    >
                        <p class="text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl">
                            {{ stat.value }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-slate-500">
                            {{ stat.label }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills to transform your career -->
        <section class="py-14 md:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mb-8 flex flex-col items-center gap-3 text-center md:mb-10">
                    <div>
                        <p class="text-sm font-semibold tracking-[0.18em] text-[#ed9134]">الدورات</p>
                        <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl md:text-[2.75rem]">
                    دورات تُغيّر مسيرتك وحياتك
                        </h2>
                    </div>
                </div>

                <!-- Category tabs (from DB) -->
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <button
                        v-for="cat in categoriesList"
                        :key="cat.id"
                        type="button"
                        class="shrink-0 rounded-full border px-4 py-2.5 text-sm font-medium transition"
                        :class="selectedCategoryId === cat.id
                            ? 'border-slate-950 bg-slate-950 text-white shadow-lg shadow-slate-950/10'
                            : 'border-white/80 bg-white text-slate-500 hover:text-slate-900'"
                        @click="selectedCategoryId = cat.id"
                    >
                        {{ cat.name }}
                    </button>
                </div>

                <!-- Course cards: horizontal scroll (from DB) -->
                <div class="mt-8">
                    <p
                        v-if="filteredCourses.length === 0"
                        class="rounded-[2rem] border border-dashed border-slate-200 bg-white py-10 text-center text-slate-500 shadow-sm"
                    >
                        لا توجد دورات حتى الآن.
                    </p>
                    <div
                        v-else
                        class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3"
                    >
                        <Link
                            v-for="course in filteredCourses"
                            :key="course.id"
                            :href="`/course/${course.id}`"
                            class="group flex h-full flex-col overflow-hidden rounded-[2rem] border border-white/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(15,23,42,0.12)]"
                        >
                            <div class="relative overflow-hidden">
                                <ImageWithFallback
                                    :src="course.thumbnail ? `/storage/${course.thumbnail}` : undefined"
                                    :alt="course.title"
                                    class="h-56 w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                />
                                <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-950/35 to-transparent" />
                            </div>
                            <div class="flex flex-1 flex-col p-5 sm:p-6">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        {{ course.category_name || 'دورة احترافية' }}
                                    </div>
                                    <span
                                        v-if="course.status === 'draft'"
                                        class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-800"
                                    >
                                        مسودة
                                    </span>
                                </div>
                                <h3 class="line-clamp-2 text-lg font-bold leading-8 text-slate-950">
                                    {{ course.title }}
                                </h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    المدرب: {{ course.instructor }}
                                </p>
                                <div class="mt-auto flex items-end justify-between gap-4 pt-6">
                                    <div>
                                        <p class="text-xs font-medium text-slate-400">السعر</p>
                                        <span class="mt-1 block text-xl font-extrabold tracking-tight text-slate-950">
                                            {{ course.price > 0 ? `${course.price.toFixed(2)} ر.س` : 'مجاني' }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition group-hover:bg-[#ed9134]">
                                        تصفح الدورة
                                        <ArrowRight class="h-4 w-4" />
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <Link
                    :href="coursesRoute()"
                    class="mt-8 inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:text-[#ed9134]"
                >
                    عرض كل الدورات
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
        </section>

        <!-- Programs section: برامج فقط -->
        <section class="py-14 md:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mb-8 flex flex-col items-center gap-3 text-center md:mb-10">
                    <div>
                        <p class="text-sm font-semibold tracking-[0.18em] text-[#ed9134]">البرامج</p>
                        <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl md:text-[2.75rem]">
                    برامج تُغيّر مسيرتك وحياتك المهنية
                        </h2>
                    </div>
                </div>

                <!-- Program cards: responsive grid -->
                <div class="mt-8">
                    <p
                        v-if="programsList.length === 0"
                        class="rounded-[2rem] border border-dashed border-slate-200 bg-white py-10 text-center text-slate-500 shadow-sm"
                    >
                        لا توجد برامج حتى الآن.
                    </p>
                    <div
                        v-else
                        class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3"
                    >
                        <Link
                            v-for="program in programsList"
                            :key="'program-' + program.id"
                            href="/programs"
                            class="group flex h-full flex-col overflow-hidden rounded-[2rem] border border-white/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(15,23,42,0.12)]"
                        >
                            <div class="relative overflow-hidden">
                                <ImageWithFallback
                                    :src="program.thumbnail ? (program.thumbnail.startsWith('http') ? program.thumbnail : `/storage/${program.thumbnail}`) : undefined"
                                    :alt="program.title"
                                    class="h-56 w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                />
                                <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-950/35 to-transparent" />
                            </div>
                            <div class="flex flex-1 flex-col p-5 sm:p-6">
                                <div class="mb-3 inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        {{ program.level || 'برنامج متخصص' }}
                                </div>
                                <h3 class="line-clamp-2 text-lg font-bold leading-8 text-slate-950">
                                    {{ program.title }}
                                </h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    {{ program.duration || 'تجربة تعليمية متقدمة' }}
                                </p>
                                <div class="mt-auto flex items-end justify-between gap-4 pt-6">
                                    <div>
                                        <p class="text-xs font-medium text-slate-400">السعر</p>
                                        <span class="mt-1 block text-xl font-extrabold tracking-tight text-slate-950">
                                            {{ program.price > 0 ? `${program.price.toFixed(2)} ر.س` : 'مجاني' }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition group-hover:bg-[#ed9134]">
                                        تصفح البرنامج
                                        <ArrowRight class="h-4 w-4" />
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <Link
                    href="/programs"
                    class="mt-8 inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:text-[#ed9134]"
                >
                    عرض كل البرامج
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
        </section>

        <!-- Features -->
        <section id="how-it-works" class="py-16 md:py-28">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <p class="text-center text-sm font-semibold uppercase tracking-[0.18em] text-[#ed9134]">
                    لماذا أكاديمية فايرير للتدريب والتعليم
                </p>
                <h2 class="mt-4 text-center text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl md:text-[2.9rem]">
                    كل ما تحتاجه للنجاح
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-center text-base leading-8 text-slate-600">
                    منصّة تدعم رحلة تعلّمك من البداية حتى النهاية.
                </p>
                <div class="mt-10 grid gap-5 md:mt-16 md:grid-cols-3 md:gap-8">
                    <div
                        v-for="(feature, i) in features"
                        :key="i"
                        class="group rounded-[2rem] border border-white/80 bg-white/90 p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(15,23,42,0.08)] md:p-8"
                    >
                        <div
                            class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-[#ed9134] transition group-hover:scale-105"
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
        <section class="py-16 md:py-28">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div
                    class="relative overflow-hidden rounded-[2.25rem] border border-slate-800 bg-[linear-gradient(135deg,#020617_0%,#0f172a_45%,#111827_100%)] p-6 text-white shadow-[0_30px_80px_rgba(15,23,42,0.3)] sm:p-8 md:p-16"
                >
                    <div
                        class="absolute right-0 top-0 h-64 w-64 rounded-full bg-[#ed9134]/20 blur-[110px]"
                    />
                    <div
                        class="absolute bottom-0 left-10 h-56 w-56 rounded-full bg-white/10 blur-[120px]"
                    />
                    <div class="relative grid gap-8 md:grid-cols-2 md:items-center md:gap-12">
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl md:text-[3rem]">
                                مستعد لبدء التعلّم؟
                            </h2>
                            <p class="mt-4 text-base leading-8 text-slate-300 sm:text-lg">
                                انضم إلى أكاديمية فايرير للتدريب والتعليم اليوم واحصل على مئات الكورسات ومدربين خبراء وشهادات معتمدة.
                            </p>
                            <ul class="mt-6 space-y-3">
                                <li
                                    v-for="(benefit, i) in benefits"
                                    :key="i"
                                    class="flex items-center gap-3 text-slate-200"
                                >
                                    <CheckCircle2 class="h-5 w-5 shrink-0 text-[#ed9134]" />
                                    <span>{{ benefit }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="flex flex-col items-stretch justify-center gap-3 md:items-end">
                            <template v-if="!authUser">
                                <Link
                                    :href="register()"
                                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-white px-6 py-4 text-base font-semibold text-slate-950 shadow-xl transition hover:bg-slate-100 sm:w-auto sm:px-8"
                                >
                                    إنشاء حساب مجاني
                                    <ArrowRight class="h-5 w-5" />
                                </Link>
                                <p class="mt-4 text-sm text-slate-400">
                                    لا حاجة لبطاقة ائتمان.
                                </p>
                            </template>
                            <template v-else>
                                <Link
                                    :href="dashboard()"
                                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-white px-6 py-4 text-base font-semibold text-slate-950 shadow-xl transition hover:bg-slate-100 sm:w-auto sm:px-8"
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
        <footer class="border-t border-slate-200 bg-white/70 backdrop-blur">
            <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-14">
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
                        <p class="mt-4 max-w-xs text-sm leading-7 text-slate-600">
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
                                <Link :href="authUser ? dashboard() : login()" class="text-sm text-slate-600 transition hover:text-[#ed9134]">
                                    {{ authUser ? 'لوحة التحكم' : 'تسجيل الدخول' }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="register()" class="text-sm text-slate-600 transition hover:text-[#ed9134]">
                                    إنشاء حساب
                                </Link>
                            </li>
                            <li>
                                <Link v-if="authUser" :href="dashboard()" class="text-sm text-slate-600 transition hover:text-[#ed9134]">
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
                                <a href="#" class="text-sm text-slate-600 transition hover:text-[#ed9134]">من نحن</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-[#ed9134]">اتصل بنا</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-[#ed9134]">الوظائف</a>
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
                                <a href="#" class="text-sm text-slate-600 transition hover:text-[#ed9134]">سياسة الخصوصية</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-[#ed9134]">الشروط والأحكام</a>
                            </li>
                            <li>
                                <a href="#" class="text-sm text-slate-600 transition hover:text-[#ed9134]">سياسة الكوكيز</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-7 text-center md:mt-12 md:flex-row md:gap-4 md:pt-8 md:text-start">
                    <p class="text-sm text-slate-500">
                        © {{ new Date().getFullYear() }} أكاديمية فايرير للتدريب والتعليم. جميع الحقوق محفوظة.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-slate-500 md:justify-start">
                        <Link :href="login()" class="transition hover:text-slate-700">تسجيل الدخول</Link>
                        <Link :href="register()" class="transition hover:text-slate-700">التسجيل</Link>
                        <Link v-if="authUser" :href="dashboard()" class="transition hover:text-slate-700">لوحة التحكم</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
