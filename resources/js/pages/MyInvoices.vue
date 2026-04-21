<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2, CreditCard, Eye, FileText, Receipt } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';

defineProps<{
    invoices: Array<{
        id: number;
        reference: string;
        description: string;
        amount: string;
        status: 'paid' | 'unpaid';
        created_at: string;
        paid_at: string | null;
        payment_method: string | null;
        items_count: number;
        view_url: string;
    }>;
}>();
</script>

<template>
    <Head title="الفواتير" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <SiteHeader />

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <p class="inline-flex items-center gap-2 rounded-full bg-[#ed9134]/10 px-4 py-1.5 text-xs font-semibold text-[#ed9134]">
                    <Receipt class="h-4 w-4" />
                    الفواتير
                </p>
                <h1 class="mt-4 text-2xl font-extrabold text-slate-900 sm:text-3xl">فواتير مشترياتك من المنصة</h1>
                <p class="mt-2 text-sm leading-7 text-slate-600">
                    ستجد هنا جميع الفواتير الخاصة بالدورات والكتب التي قمت بشرائها مع تفاصيل كل فاتورة وحالة الدفع.
                </p>
            </section>

            <section class="mt-8">
                <div
                    v-if="invoices.length === 0"
                    class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm"
                >
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <FileText class="h-7 w-7 text-slate-500" />
                    </div>
                    <h2 class="mt-5 text-xl font-bold text-slate-900">لا توجد فواتير حتى الآن</h2>
                    <p class="mx-auto mt-2 max-w-2xl text-sm leading-7 text-slate-600">
                        ستظهر فواتيرك هنا تلقائيًا بعد إتمام أي عملية شراء من الموقع.
                    </p>
                </div>

                <div
                    v-else
                    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-right">
                            <thead class="bg-slate-50">
                                <tr class="text-xs font-bold text-slate-500">
                                    <th class="px-5 py-4">رقم الفاتورة</th>
                                    <th class="px-5 py-4">الوصف</th>
                                    <th class="px-5 py-4">عدد العناصر</th>
                                    <th class="px-5 py-4">المبلغ</th>
                                    <th class="px-5 py-4">الحالة</th>
                                    <th class="px-5 py-4">تاريخ الإنشاء</th>
                                    <th class="px-5 py-4">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr
                                    v-for="invoice in invoices"
                                    :key="invoice.id"
                                    class="transition hover:bg-slate-50/80"
                                >
                                    <td class="px-5 py-4 align-top">
                                        <p class="font-bold text-slate-900">{{ invoice.reference }}</p>
                                        <p
                                            v-if="invoice.payment_method || invoice.paid_at"
                                            class="mt-1 text-xs text-slate-500"
                                        >
                                            {{ invoice.payment_method || 'طريقة الدفع غير متاحة' }}
                                            <span v-if="invoice.paid_at"> • {{ invoice.paid_at }}</span>
                                        </p>
                                    </td>
                                    <td class="px-5 py-4 align-top text-sm text-slate-700">
                                        {{ invoice.description }}
                                    </td>
                                    <td class="px-5 py-4 align-top text-sm font-semibold text-slate-700">
                                        {{ invoice.items_count }}
                                    </td>
                                    <td class="px-5 py-4 align-top text-sm font-extrabold text-[#ed9134]">
                                        {{ invoice.amount }}
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold"
                                            :class="invoice.status === 'paid'
                                                ? 'bg-emerald-50 text-emerald-700'
                                                : 'bg-amber-50 text-amber-700'"
                                        >
                                            <CheckCircle2
                                                v-if="invoice.status === 'paid'"
                                                class="h-4 w-4"
                                            />
                                            <CreditCard
                                                v-else
                                                class="h-4 w-4"
                                            />
                                            {{ invoice.status === 'paid' ? 'مدفوع' : 'بانتظار الدفع' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 align-top text-sm text-slate-600">
                                        {{ invoice.created_at }}
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <Link
                                            :href="invoice.view_url"
                                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                                        >
                                            <Eye class="h-4 w-4" />
                                            عرض
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
