<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { BookOpen, ShoppingCart } from 'lucide-vue-next';
import { ref } from 'vue';
import SiteHeader from '@/components/SiteHeader.vue';

type DigitalBook = {
    id: number;
    title: string;
    price: number;
    purchase_count: number;
    cover?: string | null;
};

const props = defineProps<{
    book: DigitalBook;
    isAuthenticated: boolean;
    isPurchased: boolean;
}>();

const fallbackCover = 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80';
const showLoginPopup = ref(false);

function addBookToCart() {
    if (!props.isAuthenticated) {
        showLoginPopup.value = true;

        return;
    }

    router.post(`/cart/add-book/${props.book.id}`);
}
</script>

<template>
    <Head :title="props.book.title" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <SiteHeader />

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-12">
            <Link
                href="/digital-library"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-[#ed9134]"
            >
                العودة إلى المكتبة الرقمية
            </Link>

            <section class="mt-6 grid gap-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:grid-cols-[minmax(0,420px)_1fr] lg:p-8">
                <div class="overflow-hidden rounded-2xl bg-slate-100">
                    <img
                        :src="props.book.cover || fallbackCover"
                        :alt="props.book.title"
                        class="h-full w-full object-cover"
                    />
                </div>

                <div class="flex flex-col justify-center">
                    <p class="inline-flex w-fit items-center gap-2 rounded-full bg-indigo-100 px-4 py-1.5 text-xs font-semibold text-indigo-700">
                        <BookOpen class="h-4 w-4" />
                        تفاصيل الكتاب
                    </p>

                    <h1 class="mt-4 text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">
                        {{ props.book.title }}
                    </h1>

                    <p class="mt-4 text-base leading-8 text-slate-600">
                        <span v-if="props.isPurchased">
                            هذا الكتاب موجود بالفعل ضمن مشترياتك ويمكنك الوصول إليه من قائمة الكتب داخل حسابك.
                        </span>
                        <span v-else>
                            كتاب رقمي متاح ضمن المكتبة الرقمية. يمكنك مراجعة السعر ثم إضافته إلى السلة لإتمام الشراء بسهولة.
                        </span>
                    </p>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">السعر</p>
                            <p class="mt-2 text-2xl font-extrabold text-[#ed9134]">
                                {{ props.book.price.toFixed(2) }} ر.س
                            </p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">عدد مرات الشراء</p>
                            <p class="mt-2 text-2xl font-extrabold text-slate-900">
                                {{ props.book.purchase_count }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <button
                            v-if="!props.isPurchased"
                            type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#ed9134] px-6 py-3 text-base font-semibold text-white transition hover:bg-[#d67d2a]"
                            @click="addBookToCart"
                        >
                            <ShoppingCart class="h-5 w-5" />
                            أضف للسلة
                        </button>
                        <Link
                            v-else
                            href="/my-books"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-base font-semibold text-white transition hover:bg-emerald-700"
                        >
                            <BookOpen class="h-5 w-5" />
                            ضمن كتبي
                        </Link>
                        <Link
                            :href="props.isPurchased ? '/my-books' : '/cart'"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-6 py-3 text-base font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            {{ props.isPurchased ? 'الذهاب إلى كتبي' : 'الذهاب إلى السلة' }}
                        </Link>
                    </div>
                </div>
            </section>
        </main>

        <div
            v-if="showLoginPopup"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/50 p-4"
        >
            <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-2xl sm:p-10">
                <h3 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    تسجيل الدخول مطلوب
                </h3>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-relaxed text-slate-600 sm:text-[1.65rem]">
                    لإضافة الكتاب إلى السلة وإتمام الشراء، يرجى تسجيل الدخول أولاً.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <Link
                        href="/login"
                        class="inline-flex min-w-48 items-center justify-center rounded-2xl bg-[#ed9134] px-6 py-3 text-xl font-bold text-white shadow-sm transition hover:bg-[#d67d2a]"
                    >
                        تسجيل الدخول
                    </Link>
                    <button
                        type="button"
                        class="inline-flex min-w-40 items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-xl font-semibold text-slate-700 transition hover:bg-slate-50"
                        @click="showLoginPopup = false"
                    >
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
