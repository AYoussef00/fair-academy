<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ArrowLeft, Check, Sparkles, Tag } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import { home, login, register, dashboard } from '@/routes';

const page = usePage();
const authUser = computed(() => (page.props.auth as { user?: unknown })?.user);

const props = defineProps<{
    courses: Array<{
        id: number;
        title: string;
        thumbnail: string | null;
        price: number;
        category_name: string;
        instructor: string;
        duration?: string | null;
        total_lessons?: number;
    }>;
    subtotal: number;
}>();

const totalFormatted = () =>
    props.subtotal > 0 ? `usd ${props.subtotal.toFixed(2)}` : 'مجاني';

function removeFromCart(courseId: number) {
    router.delete(`/cart/remove/${courseId}`);
}

function thumbnailSrc(thumbnail: string | null) {
    if (!thumbnail) return undefined;
    return thumbnail.startsWith('http') ? thumbnail : `/storage/${thumbnail}`;
}

function priceFormatted(price: number) {
    return price > 0 ? `usd ${price.toFixed(2)}` : 'مجاني';
}
</script>

<template>
    <Head title="عربة التسوق – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-screen flex-col bg-white">
        <SiteHeader />
        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <div class="mb-6 flex items-center gap-4">
                <Link
                    :href="home()"
                    class="flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-violet-600"
                >
                    <ArrowLeft class="h-4 w-4" />
                    متابعة التصفح
                </Link>
            </div>

            <div
                v-if="courses.length === 0"
                class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 p-12 text-center"
            >
                <h1 class="text-2xl font-bold text-slate-900">عربة التسوق</h1>
                <p class="mt-2 text-slate-600">السلة فارغة.</p>
                <Link
                    :href="home()"
                    class="mt-6 inline-flex items-center rounded-xl bg-violet-600 px-6 py-3 text-sm font-semibold text-white hover:bg-violet-700"
                >
                    تصفح الدورات
                </Link>
            </div>

            <template v-else>
                <h1 class="text-3xl font-bold text-slate-900">عربة التسوق</h1>
                <p class="mt-1 text-slate-600">
                    يوجد {{ courses.length }} من الدورات في السلة
                </p>

                <div class="mt-8 grid gap-8 lg:grid-cols-3">
                    <!-- قائمة الدورات -->
                    <div class="lg:col-span-2 space-y-6">
                        <div
                            v-for="course in courses"
                            :key="course.id"
                            class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-start"
                        >
                            <Link
                                :href="`/course/${course.id}`"
                                class="h-36 w-full shrink-0 overflow-hidden rounded-lg bg-slate-100 sm:h-28 sm:w-44"
                            >
                                <ImageWithFallback
                                    :src="thumbnailSrc(course.thumbnail)"
                                    :alt="course.title"
                                    class="h-full w-full object-cover"
                                />
                            </Link>
                            <div class="min-w-0 flex-1">
                                <Link
                                    :href="`/course/${course.id}`"
                                    class="font-semibold text-slate-900 hover:text-violet-600 line-clamp-2"
                                >
                                    {{ course.title }}
                                </Link>
                                <p class="mt-1 text-sm text-slate-500">
                                    بواسطة {{ course.instructor }}
                                </p>
                                <span class="mt-2 inline-block rounded bg-sky-100 px-2 py-0.5 text-xs font-medium text-sky-800">
                                    الأكثر مبيعاً
                                </span>
                                <p class="mt-2 text-xs text-slate-500">
                                    <span v-if="course.duration">{{ course.duration }}</span>
                                    <template v-if="course.total_lessons">
                                        <span v-if="course.duration"> • </span>
                                        {{ course.total_lessons }} من المحاضرات
                                    </template>
                                    <span> • جميع المستويات</span>
                                </p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded bg-violet-100 px-2 py-0.5 text-xs font-medium text-violet-800">
                                        <Check class="h-3 w-3" />
                                        مدفوع
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-start justify-between gap-2 border-t border-slate-100 pt-4 sm:border-t-0 sm:border-s sm:border-slate-200 sm:pt-0 sm:ps-4">
                                <div class="flex items-center gap-2">
                                    <Tag class="h-4 w-4 text-violet-600" />
                                    <span class="text-lg font-bold text-slate-900">
                                        {{ priceFormatted(course.price) }}
                                    </span>
                                </div>
                                <div class="flex gap-4 text-sm">
                                    <button
                                        type="button"
                                        class="font-medium text-red-600 hover:underline"
                                        @click="removeFromCart(course.id)"
                                    >
                                        إزالة
                                    </button>
                                    <button
                                        type="button"
                                        class="font-medium text-slate-600 hover:underline"
                                    >
                                        حفظ لوقت لاحق
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملخص الطلب -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-24 rounded-2xl border border-slate-200 bg-slate-50/50 p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900">الإجمالي:</h2>
                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ totalFormatted() }}
                            </p>
                            <Link
                                href="/checkout"
                                class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-violet-600 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-violet-700"
                            >
                                الانتقال إلى الدفع
                                <ArrowLeft class="h-4 w-4 rtl:rotate-180" />
                            </Link>
                            <p class="mt-3 text-center text-xs text-slate-500">
                                لن يتم خصم أي مبلغ منك حتى الآن
                            </p>
                            <div class="mt-6 border-t border-slate-200 pt-4">
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        placeholder="رمز القسيمة"
                                        class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500"
                                    />
                                    <button
                                        type="button"
                                        class="rounded-lg bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700 transition hover:bg-violet-200"
                                    >
                                        تطبيق القسيمة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <div class="lg:col-span-1">
                        <Link :href="home()" class="inline-flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-600">
                                <Sparkles class="h-5 w-5 text-white" />
                            </div>
                            <span class="text-xl font-bold text-slate-900">أكاديمية فايرير للتدريب والتعليم</span>
                        </Link>
                        <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-600">
                            تعلّم بلا حدود. أتقن مهارات جديدة مع كورسات يقدّمها خبراء واحصل على شهادات معتمدة.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-900">المنصة</h4>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <Link :href="authUser ? dashboard() : login()" class="text-sm text-slate-600 transition hover:text-violet-600">
                                    {{ authUser ? 'لوحة التحكم' : 'تسجيل الدخول' }}
                                </Link>
                            </li>
                            <li>
                                <Link :href="register()" class="text-sm text-slate-600 transition hover:text-violet-600">إنشاء حساب</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-900">الشركة</h4>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">من نحن</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">اتصل بنا</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-900">قانوني</h4>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">سياسة الخصوصية</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition hover:text-violet-600">الشروط والأحكام</a></li>
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
