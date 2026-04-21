<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowLeft, CreditCard } from 'lucide-vue-next';
import { computed } from 'vue';
import SiteHeader from '@/components/SiteHeader.vue';

const page = usePage();
const flash = computed(() => (page.props.flash as { error?: string | null; success?: string | null }) ?? {});

const props = defineProps<{
    items: Array<{
        key: string;
        id: number;
        type: 'course' | 'book';
        title: string;
        price: number;
    }>;
    subtotal: number;
}>();

function submit() {
    window.location.href = '/checkout/redirect';
}

const totalFormatted = () =>
    props.subtotal > 0 ? `usd ${props.subtotal.toFixed(2)}` : 'مجاني';
</script>

<template>
    <Head title="الدفع – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-screen flex-col bg-white">
        <SiteHeader />
        <main class="mx-auto w-full max-w-4xl flex-1 px-4 py-8 sm:px-6">
            <Link
                href="/cart"
                class="mb-6 inline-flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-violet-600"
            >
                <ArrowLeft class="h-4 w-4" />
                العودة إلى السلة
            </Link>

            <h1 class="text-2xl font-bold text-slate-900">إتمام الدفع</h1>
            <p class="mt-1 text-slate-600">
                سيتم تحويلك إلى صفحة Noon الآمنة لإكمال الدفع بالمبلغ الإجمالي.
            </p>
            <div
                v-if="flash.error"
                class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                {{ flash.error }}
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-6">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                        <CreditCard class="h-5 w-5 text-violet-600" />
                        الدفع عبر Noon
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        عند الضغط على الزر التالي سيتم إنشاء عملية الدفع بقيمة الطلب كاملة ثم تحويلك مباشرة
                        إلى بوابة Noon لإدخال بيانات البطاقة هناك بشكل آمن.
                    </p>
                    <button
                        type="button"
                        class="mt-6 inline-flex h-12 w-full items-center justify-center rounded-xl bg-violet-600 px-6 text-base font-semibold text-white shadow-lg transition hover:bg-violet-700"
                        @click="submit"
                    >
                        الذهاب إلى نون للدفع
                    </button>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-6">
                    <h2 class="text-lg font-bold text-slate-900">ملخص الطلب</h2>
                    <ul class="mt-4 space-y-3">
                        <li
                            v-for="item in items"
                            :key="item.key"
                            class="flex justify-between text-sm"
                        >
                            <span class="text-slate-700 line-clamp-1">{{ item.title }}</span>
                            <span class="shrink-0 font-semibold text-slate-900">
                                {{ item.price > 0 ? `$${item.price.toFixed(2)}` : 'مجاني' }}
                            </span>
                        </li>
                    </ul>
                    <div class="mt-4 border-t border-slate-200 pt-4">
                        <div class="flex justify-between text-lg font-bold text-slate-900">
                            <span>الإجمالي</span>
                            <span>{{ totalFormatted() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
