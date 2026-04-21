<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ArrowLeft, Check, ShieldCheck, ShoppingBag, Tag } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';
import ImageWithFallback from '@/components/ImageWithFallback.vue';
import { home, login, register, dashboard } from '@/routes';

const page = usePage();
const authUser = computed(() => (page.props.auth as { user?: unknown })?.user);
const flash = computed(() => (page.props.flash as { error?: string | null; success?: string | null }) ?? {});

const props = defineProps<{
    items: Array<{
        key: string;
        id: number;
        type: 'course' | 'book';
        title: string;
        image: string | null;
        price: number;
        subtitle: string;
        meta: string;
        badge: string;
        detail_url: string;
        remove_url: string;
    }>;
    subtotal: number;
}>();

const totalFormatted = () =>
    props.subtotal > 0 ? `${props.subtotal.toFixed(2)} ر.س` : 'مجاني';

function removeFromCartItem(removeUrl: string) {
    router.delete(removeUrl);
}

function imageSrc(image: string | null) {
    if (!image) return undefined;
    return image.startsWith('http') || image.startsWith('/') ? image : `/storage/${image}`;
}

function priceFormatted(price: number) {
    return price > 0 ? `${price.toFixed(2)} ر.س` : 'مجاني';
}

function proceedToNoonCheckout() {
    window.location.href = '/checkout/redirect';
}
</script>

<template>
    <Head title="عربة التسوق – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-screen flex-col bg-[#f5f5f7] text-slate-900">
        <SiteHeader />
        <main class="relative mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 sm:py-10">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 overflow-hidden">
                <div class="mx-auto h-64 max-w-6xl rounded-full bg-[radial-gradient(circle_at_center,_rgba(147,197,253,0.22),_transparent_60%)] blur-3xl" />
            </div>

            <div class="mb-8 flex items-center gap-4">
                <Link
                    :href="home()"
                    class="inline-flex items-center gap-2 rounded-full border border-white/70 bg-white/80 px-4 py-2 text-sm font-medium text-slate-600 shadow-sm backdrop-blur transition hover:text-slate-900"
                >
                    <ArrowLeft class="h-4 w-4" />
                    متابعة التصفح
                </Link>
            </div>

            <section class="rounded-[2rem] border border-white/70 bg-white/75 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur sm:p-8 lg:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-1.5 text-xs font-semibold text-white">
                            <ShoppingBag class="h-4 w-4" />
                            عربة التسوق
                        </div>
                        <h1 class="mt-5 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">
                            تجربة دفع أنظف، أسرع، وأوضح.
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-600">
                            راجع مشترياتك بهدوء، ثم انتقل إلى صفحة الدفع الآمنة لإتمام الطلب. تصميم السلة الآن
                            يركّز على الوضوح، المسافات المريحة، وسهولة اتخاذ القرار.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[320px] lg:max-w-sm">
                        <div class="rounded-3xl bg-slate-50 p-5 ring-1 ring-slate-200/80">
                            <p class="text-sm font-medium text-slate-500">عدد المنتجات</p>
                            <p class="mt-2 text-2xl font-bold text-slate-950">{{ items.length }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 text-white shadow-lg">
                            <p class="text-sm font-medium text-white/70">الإجمالي الحالي</p>
                            <p class="mt-2 text-2xl font-bold">{{ totalFormatted() }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div
                v-if="flash.error"
                class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 shadow-sm"
            >
                {{ flash.error }}
            </div>

            <div
                v-if="items.length === 0"
                class="mt-8 rounded-[2rem] border border-dashed border-slate-300 bg-white/80 p-12 text-center shadow-sm"
            >
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                    <ShoppingBag class="h-9 w-9 text-slate-500" />
                </div>
                <h2 class="mt-6 text-2xl font-bold text-slate-950">السلة فارغة</h2>
                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-600">
                    ابدأ بإضافة الكتب أو الدورات التي تريدها، وسيظهر كل شيء هنا في واجهة أوضح قبل الدفع.
                </p>
                <Link
                    href="/digital-library"
                    class="mt-8 inline-flex items-center rounded-full bg-slate-950 px-7 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    تصفح الكتب والدورات
                </Link>
            </div>

            <div v-else class="mt-8 grid gap-8 xl:grid-cols-[minmax(0,1.35fr)_380px]">
                <section class="space-y-5">
                    <article
                        v-for="item in items"
                        :key="item.key"
                        class="group rounded-[2rem] border border-white/80 bg-white p-4 shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_22px_60px_rgba(15,23,42,0.10)] sm:p-5"
                    >
                        <div class="flex flex-col gap-5 sm:flex-row">
                            <Link
                                :href="item.detail_url"
                                class="h-44 w-full shrink-0 overflow-hidden rounded-[1.5rem] bg-slate-100 sm:h-36 sm:w-52"
                            >
                                <ImageWithFallback
                                    :src="imageSrc(item.image)"
                                    :alt="item.title"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                />
                            </Link>

                            <div class="flex min-w-0 flex-1 flex-col justify-between gap-5">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ item.badge }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            <Check class="h-3.5 w-3.5" />
                                            جاهز للدفع
                                        </span>
                                    </div>

                                    <Link
                                        :href="item.detail_url"
                                        class="mt-4 block text-xl font-bold leading-tight text-slate-950 transition hover:text-slate-700"
                                    >
                                        {{ item.title }}
                                    </Link>

                                    <p class="mt-2 text-sm font-medium text-slate-500">
                                        {{ item.subtitle }}
                                    </p>

                                    <p class="mt-3 text-sm leading-7 text-slate-500">
                                        {{ item.meta }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-4 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="inline-flex items-center gap-2 text-slate-900">
                                        <Tag class="h-4 w-4 text-slate-500" />
                                        <span class="text-xl font-bold">{{ priceFormatted(item.price) }}</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 text-sm">
                                        <button
                                            type="button"
                                            class="rounded-full border border-slate-200 px-4 py-2 font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                                            @click="removeFromCartItem(item.remove_url)"
                                        >
                                            إزالة من السلة
                                        </button>
                                        <Link
                                            :href="item.detail_url"
                                            class="rounded-full bg-slate-950 px-4 py-2 font-medium text-white transition hover:bg-slate-800"
                                        >
                                            عرض التفاصيل
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>

                <aside class="xl:pt-4">
                    <div class="sticky top-24 overflow-hidden rounded-[2rem] border border-slate-200/80 bg-[linear-gradient(180deg,#ffffff_0%,#f8fafc_100%)] p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] sm:p-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-white">
                                <ShieldCheck class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500">ملخص الدفع</p>
                                <h2 class="text-xl font-bold text-slate-950">جاهز للانتقال إلى Noon</h2>
                            </div>
                        </div>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-center justify-between text-sm text-slate-500">
                                <span>عدد العناصر</span>
                                <span class="font-semibold text-slate-900">{{ items.length }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-slate-500">
                                <span>حالة الطلب</span>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    جاهز للدفع
                                </span>
                            </div>
                            <div class="border-t border-slate-200 pt-4">
                                <div class="flex items-end justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">الإجمالي النهائي</p>
                                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">
                                            {{ totalFormatted() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="mt-8 flex w-full items-center justify-center gap-2 rounded-full bg-slate-950 py-4 text-sm font-semibold text-white shadow-lg transition hover:bg-slate-800"
                            @click="proceedToNoonCheckout"
                        >
                            الانتقال للدفع عبر نون
                            <ArrowLeft class="h-4 w-4 rtl:rotate-180" />
                        </button>

                        <p class="mt-4 text-center text-xs leading-6 text-slate-500">
                            سيتم تحويلك إلى صفحة دفع آمنة لإكمال العملية. لن يتم خصم أي مبلغ قبل تأكيد الدفع.
                        </p>
                    </div>
                </aside>
            </div>
        </main>

        <footer class="mt-auto border-t border-white/70 bg-white/70 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <div class="lg:col-span-1">
                        <Link :href="home()" class="inline-flex items-center gap-2">
                            <img
                                src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                                alt="أكاديمية فايرير للتدريب والتعليم"
                                class="h-11 w-auto object-contain"
                            />
                            <span class="text-xl font-bold text-slate-900">أكاديمية فايرير للتدريب والتعليم</span>
                        </Link>
                        <p class="mt-4 max-w-xs text-sm leading-7 text-slate-600">
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
