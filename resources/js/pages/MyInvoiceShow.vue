<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, CreditCard, Download, Receipt, UserRound } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';

const props = defineProps<{
    invoice: {
        id: number;
        reference: string;
        description: string;
        amount: string;
        subtotal: string;
        status: 'paid' | 'unpaid';
        created_at: string;
        paid_at: string | null;
        payment_method: string;
        transaction_id: string | null;
        customer_name: string;
        customer_email: string;
        items: Array<{
            id: number;
            title: string;
            type: string;
            price: string;
            quantity: number;
        }>;
    };
}>();

function printInvoice() {
    window.print();
}
</script>

<template>
    <Head :title="`الفاتورة ${props.invoice.reference}`" />

    <div class="min-h-screen bg-slate-100 text-slate-900 print:bg-white">
        <SiteHeader />

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-12 print:max-w-none print:px-0 print:py-0">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3 print:hidden">
                <Link
                    href="/my-invoices"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    <ArrowLeft class="h-4 w-4" />
                    العودة إلى الفواتير
                </Link>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-2xl bg-[#ed9134] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                    @click="printInvoice"
                >
                    <Download class="h-4 w-4" />
                    طباعة الفاتورة
                </button>
            </div>

            <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.08)] print:rounded-none print:border-0 print:shadow-none">
                <div class="border-b border-slate-200 bg-gradient-to-l from-[#fff7ed] via-white to-white px-6 py-8 sm:px-10">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-[#ed9134]/10 px-4 py-1.5 text-xs font-bold text-[#ed9134]">
                                <Receipt class="h-4 w-4" />
                                فاتورة رسمية
                            </div>
                            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">
                                {{ props.invoice.reference }}
                            </h1>
                            <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-600">
                                {{ props.invoice.description }}
                            </p>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                            <img
                                src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                                alt="eacademy"
                                class="h-14 w-auto object-contain"
                            />
                            <p class="mt-3 text-sm font-bold text-slate-900">eacademy</p>
                            <p class="mt-1 text-xs leading-6 text-slate-500">
                                منصة التدريب والتعليم الرقمي
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 border-b border-slate-200 px-6 py-6 sm:grid-cols-2 xl:grid-cols-4 sm:px-10">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold text-slate-500">حالة الفاتورة</p>
                        <p
                            class="mt-3 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold"
                            :class="props.invoice.status === 'paid'
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-amber-50 text-amber-700'"
                        >
                            <CheckCircle2
                                v-if="props.invoice.status === 'paid'"
                                class="h-4 w-4"
                            />
                            <CreditCard
                                v-else
                                class="h-4 w-4"
                            />
                            {{ props.invoice.status === 'paid' ? 'مدفوعة' : 'بانتظار الدفع' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold text-slate-500">تاريخ الإنشاء</p>
                        <p class="mt-3 text-sm font-bold text-slate-900">{{ props.invoice.created_at }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold text-slate-500">طريقة الدفع</p>
                        <p class="mt-3 text-sm font-bold text-slate-900">{{ props.invoice.payment_method }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-bold text-slate-500">إجمالي الفاتورة</p>
                        <p class="mt-3 text-lg font-extrabold text-[#ed9134]">{{ props.invoice.amount }}</p>
                    </div>
                </div>

                <div class="grid gap-6 px-6 py-8 sm:px-10 lg:grid-cols-[1.15fr_0.85fr]">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">تفاصيل المشتريات</h2>
                        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200">
                            <table class="min-w-full text-right">
                                <thead class="bg-slate-50">
                                    <tr class="text-xs font-bold text-slate-500">
                                        <th class="px-4 py-4">العنصر</th>
                                        <th class="px-4 py-4">النوع</th>
                                        <th class="px-4 py-4">الكمية</th>
                                        <th class="px-4 py-4">السعر</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="item in props.invoice.items"
                                        :key="item.id"
                                        class="bg-white"
                                    >
                                        <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ item.title }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ item.type }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ item.quantity }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-bold text-slate-900">
                                            {{ item.price }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-center gap-2">
                                <UserRound class="h-5 w-5 text-[#ed9134]" />
                                <h2 class="text-lg font-bold text-slate-900">بيانات العميل</h2>
                            </div>
                            <div class="mt-4 space-y-3 text-sm text-slate-700">
                                <p><span class="font-bold text-slate-900">الاسم:</span> {{ props.invoice.customer_name }}</p>
                                <p><span class="font-bold text-slate-900">البريد الإلكتروني:</span> {{ props.invoice.customer_email }}</p>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900">ملخص الدفع</h2>
                            <div class="mt-4 space-y-3 text-sm">
                                <div class="flex items-center justify-between gap-4 text-slate-600">
                                    <span>المجموع الفرعي</span>
                                    <span class="font-semibold text-slate-900">{{ props.invoice.subtotal }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-4 text-slate-600">
                                    <span>الإجمالي</span>
                                    <span class="text-lg font-extrabold text-[#ed9134]">{{ props.invoice.amount }}</span>
                                </div>
                                <div
                                    v-if="props.invoice.transaction_id"
                                    class="flex items-start justify-between gap-4 border-t border-slate-100 pt-3 text-slate-600"
                                >
                                    <span>رقم العملية</span>
                                    <span class="max-w-[16rem] break-all text-left font-mono text-xs text-slate-900">
                                        {{ props.invoice.transaction_id }}
                                    </span>
                                </div>
                                <div
                                    v-if="props.invoice.paid_at"
                                    class="flex items-center justify-between gap-4 text-slate-600"
                                >
                                    <span>تاريخ السداد</span>
                                    <span class="font-semibold text-slate-900">{{ props.invoice.paid_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
