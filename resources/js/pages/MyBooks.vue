<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, Receipt, ShoppingBag } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';

defineProps<{
    books: Array<{
        id: number;
        title: string;
        cover: string | null;
        price: number;
        purchased_at: string;
        invoice_reference: string;
        detail_url: string;
    }>;
}>();

const fallbackCover = 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80';
</script>

<template>
    <Head title="كتبي" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <SiteHeader />

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="inline-flex items-center gap-2 rounded-full bg-[#ed9134]/10 px-4 py-1.5 text-xs font-semibold text-[#ed9134]">
                            <BookOpen class="h-4 w-4" />
                            الكتب المشتراة
                        </p>
                        <h1 class="mt-4 text-2xl font-extrabold text-slate-900 sm:text-3xl">كل الكتب التي اشتريتها من المنصة</h1>
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            يمكنك الرجوع إلى كتبك في أي وقت وفتح صفحة كل كتاب للاطلاع عليه.
                        </p>
                    </div>

                    <Link
                        href="/digital-library"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        تصفح المكتبة
                    </Link>
                </div>
            </section>

            <section class="mt-8">
                <div
                    v-if="books.length === 0"
                    class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <ShoppingBag class="h-7 w-7 text-slate-500" />
                    </div>
                    <h2 class="mt-5 text-xl font-bold text-slate-900">لا توجد كتب مشتراة حتى الآن</h2>
                    <p class="mx-auto mt-2 max-w-2xl text-sm leading-7 text-slate-600">
                        بعد شراء أي كتاب من المكتبة الرقمية سيظهر هنا مباشرة داخل حسابك.
                    </p>
                </div>

                <div
                    v-else
                    class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3"
                >
                    <article
                        v-for="book in books"
                        :key="book.id"
                        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >
                        <img
                            :src="book.cover || fallbackCover"
                            :alt="book.title"
                            class="h-64 w-full object-cover"
                        />

                        <div class="p-5">
                            <h2 class="line-clamp-2 text-lg font-bold text-slate-900">
                                {{ book.title }}
                            </h2>

                            <div class="mt-4 space-y-2 text-sm text-slate-600">
                                <p>تاريخ الشراء: <span class="font-semibold text-slate-800">{{ book.purchased_at }}</span></p>
                                <p>السعر: <span class="font-semibold text-[#ed9134]">{{ book.price.toFixed(2) }} ر.س</span></p>
                                <p class="inline-flex items-center gap-2">
                                    <Receipt class="h-4 w-4" />
                                    <span>{{ book.invoice_reference }}</span>
                                </p>
                            </div>

                            <div class="mt-5">
                                <Link
                                    :href="book.detail_url"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#ed9134] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#d67d2a]"
                                >
                                    <BookOpen class="h-4 w-4" />
                                    عرض الكتاب
                                </Link>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </main>
    </div>
</template>
