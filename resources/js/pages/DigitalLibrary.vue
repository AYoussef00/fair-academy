<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen } from 'lucide-vue-next';
import { ref } from 'vue';
import SiteHeader from '@/components/SiteHeader.vue';

type DigitalBook = {
    id: number;
    title: string;
    price: number;
    purchase_count: number;
    cover?: string | null;
    status?: string;
};

const props = withDefaults(
    defineProps<{
        approvedBooks?: DigitalBook[];
        isAuthenticated?: boolean;
    }>(),
    {
        approvedBooks: () => [],
        isAuthenticated: false,
    }
);

const fallbackCover = 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80';
const showLoginPopup = ref(false);

function handleBookClick() {
    if (!props.isAuthenticated) {
        showLoginPopup.value = true;
    }
}
</script>

<template>
    <Head title="المكتبة الرقمية" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <SiteHeader />

        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16">
                <p class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-4 py-1.5 text-xs font-semibold text-indigo-700">
                    <BookOpen class="h-4 w-4" />
                    متجر الكتب الرقمية
                </p>
                <h1 class="mt-4 text-3xl font-extrabold sm:text-4xl">
                    المكتبة الرقمية
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    تصفّح الكتب المتاحة، وكل كتاب له سعر واضح يمكنك شراءه لاحقًا.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">
                    الكتب المعتمدة والمنشورة
                </h2>
                <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ props.approvedBooks.length }} كتاب
                </span>
            </div>

            <div
                v-if="props.approvedBooks.length === 0"
                class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500"
            >
                لا توجد كتب معتمدة لك حالياً.
            </div>

            <div v-else class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="book in props.approvedBooks"
                    :key="book.id"
                    class="cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                    @click="handleBookClick"
                >
                    <img
                        :src="book.cover || fallbackCover"
                        :alt="book.title"
                        class="h-56 w-full object-cover"
                    />

                    <div class="space-y-3 p-5">
                        <h3 class="line-clamp-2 text-lg font-bold leading-snug text-slate-900">
                            {{ book.title }}
                        </h3>
                        <p class="text-lg font-extrabold text-[#ed9134]">
                            {{ book.price.toFixed(2) }} ر.س
                        </p>
                        <p class="text-xs font-medium text-slate-500">
                            عدد مرات الشراء: {{ book.purchase_count }}
                        </p>
                    </div>
                </article>
            </div>
        </section>

        <div
            v-if="showLoginPopup"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/50 p-4"
        >
            <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-2xl sm:p-10">
                <h3 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    تسجيل الدخول مطلوب
                </h3>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-relaxed text-slate-600 sm:text-[1.65rem]">
                    للوصول إلى تفاصيل الكتاب وإتمام الشراء، يرجى تسجيل الدخول أولاً.
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
