<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    BookText,
    CreditCard,
    DollarSign,
    FolderOpen,
    GraduationCap,
    LayoutDashboard,
    Newspaper,
    Receipt,
    TrendingUp,
    UserRound,
    Wallet,
} from 'lucide-vue-next';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
    { title: 'المدفوعات', href: '/admin/payments' },
];

defineProps<{
    stats: {
        totalRevenue: number;
        monthRevenue: number;
        paymentsCount: number;
        paidInvoicesCount: number;
    };
    paymentMethods: Array<{
        name: string;
        payments_count: number;
        total_amount: string;
    }>;
    recentPayments: Array<{
        id: number;
        invoice_reference: string;
        customer_name: string;
        customer_email: string;
        items_summary: string;
        amount: string;
        payment_method: string;
        status: string;
        paid_at: string;
        transaction_id: string | null;
    }>;
    recentInvoices: Array<{
        id: number;
        reference: string;
        customer_name: string;
        description: string;
        items_count: number;
        total: string;
        status: string;
        payment_method: string;
        created_at: string;
    }>;
}>();

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: false },
    { title: 'المستخدمين', href: '/admin/users', icon: UserRound, active: false },
    { title: 'الطلاب', href: '/admin/students', icon: GraduationCap, active: false },
    { title: 'الدورات', href: '/admin/courses', icon: BookOpen, active: false },
    { title: 'البرامج', href: '/admin/programs', icon: FolderOpen, active: false },
    { title: 'الكتب الرقمية', href: '/admin/digital-books', icon: BookText, active: false },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: false },
    { title: 'المدفوعات', href: '/admin/payments', icon: CreditCard, active: true },
    { title: 'التقارير', href: null, icon: BarChart3, active: false },
];

function formatCurrency(value: number) {
    return `${value.toFixed(2)} ر.س`;
}
</script>

<template>
    <Head title="المدفوعات - لوحة المدير" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-slate-100/70 dark:bg-slate-950">
            <div class="flex w-full">
                <aside class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block">
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Admin</p>
                            <p class="mt-0.5 text-xs text-slate-500">مدير النظام</p>
                        </div>
                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="item in sidebarItems" :key="item.title">
                                    <Link
                                        v-if="item.href"
                                        :href="item.href"
                                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150"
                                        :class="item.active
                                            ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100'"
                                    >
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                            :class="item.active ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'"
                                        >
                                            <component :is="item.icon" class="h-4 w-4" />
                                        </span>
                                        <span class="leading-tight">{{ item.title }}</span>
                                    </Link>
                                    <button
                                        v-else
                                        type="button"
                                        class="flex w-full cursor-default items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-400"
                                    >
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800">
                                            <component :is="item.icon" class="h-4 w-4" />
                                        </span>
                                        <span class="leading-tight">{{ item.title }}</span>
                                        <span class="me-auto rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500 dark:bg-slate-800 dark:text-slate-400">قريباً</span>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <main class="min-w-0 flex-1">
                    <div class="w-full space-y-6 px-6 py-6">
                        <div class="rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">المدفوعات والإيرادات</h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                عرض منظم لكل الفواتير والمدفوعات والإيرادات المحققة داخل المنصة.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                            <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-slate-500">إجمالي الإيرادات</p>
                                        <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                            {{ formatCurrency(stats.totalRevenue) }}
                                        </p>
                                    </div>
                                    <div class="rounded-lg bg-emerald-50 p-2 text-emerald-600">
                                        <Wallet :size="20" />
                                    </div>
                                </div>
                            </Card>

                            <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-slate-500">إيرادات هذا الشهر</p>
                                        <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                            {{ formatCurrency(stats.monthRevenue) }}
                                        </p>
                                    </div>
                                    <div class="rounded-lg bg-blue-50 p-2 text-blue-600">
                                        <TrendingUp :size="20" />
                                    </div>
                                </div>
                            </Card>

                            <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-slate-500">عدد المدفوعات الناجحة</p>
                                        <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                            {{ stats.paymentsCount }}
                                        </p>
                                    </div>
                                    <div class="rounded-lg bg-[#ed9134]/10 p-2 text-[#ed9134]">
                                        <DollarSign :size="20" />
                                    </div>
                                </div>
                            </Card>

                            <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-slate-500">الفواتير المدفوعة</p>
                                        <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-slate-100">
                                            {{ stats.paidInvoicesCount }}
                                        </p>
                                    </div>
                                    <div class="rounded-lg bg-violet-50 p-2 text-violet-600">
                                        <Receipt :size="20" />
                                    </div>
                                </div>
                            </Card>
                        </div>

                        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
                            <Card class="border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">قنوات الدفع</h2>
                                <p class="mt-1 text-sm text-slate-500">توزيع الإيرادات حسب طريقة الدفع.</p>

                                <div class="mt-5 space-y-3">
                                    <div
                                        v-for="method in paymentMethods"
                                        :key="method.name"
                                        class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3"
                                    >
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ method.name }}</p>
                                            <p class="text-xs text-slate-500">{{ method.payments_count }} عملية دفع</p>
                                        </div>
                                        <p class="text-sm font-bold text-slate-900">{{ method.total_amount }}</p>
                                    </div>
                                    <div
                                        v-if="paymentMethods.length === 0"
                                        class="rounded-2xl border border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-500"
                                    >
                                        لا توجد مدفوعات مسجلة حتى الآن.
                                    </div>
                                </div>
                            </Card>

                            <Card class="border-slate-200 bg-white p-0 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-6 py-5">
                                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">أحدث الفواتير</h2>
                                    <p class="mt-1 text-sm text-slate-500">آخر الفواتير المولدة داخل النظام.</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-700">الفاتورة</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-700">العميل</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-700">عدد العناصر</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-700">الإجمالي</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-700">الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <tr v-for="invoice in recentInvoices" :key="invoice.id">
                                                <td class="px-4 py-3">
                                                    <p class="font-semibold text-slate-900">{{ invoice.reference }}</p>
                                                    <p class="mt-1 text-xs text-slate-500">{{ invoice.created_at }}</p>
                                                </td>
                                                <td class="px-4 py-3 text-slate-600">{{ invoice.customer_name }}</td>
                                                <td class="px-4 py-3 text-slate-600">{{ invoice.items_count }}</td>
                                                <td class="px-4 py-3 font-semibold text-slate-900">{{ invoice.total }}</td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                                                        :class="invoice.status === 'paid'
                                                            ? 'bg-emerald-50 text-emerald-700'
                                                            : 'bg-amber-50 text-amber-700'"
                                                    >
                                                        {{ invoice.status === 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="recentInvoices.length === 0">
                                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">
                                                    لا توجد فواتير حتى الآن.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </Card>
                        </div>

                        <Card class="overflow-hidden border-slate-200 bg-white p-0 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-100 px-6 py-5">
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">سجل المدفوعات</h2>
                                <p class="mt-1 text-sm text-slate-500">تفاصيل كل عملية دفع ناجحة مع رقم العملية والعميل والعناصر.</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100 text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">الفاتورة</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">العميل</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">المحتوى</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">المبلغ</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">طريقة الدفع</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">التاريخ</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700">رقم العملية</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr v-for="payment in recentPayments" :key="payment.id">
                                            <td class="px-4 py-3 font-semibold text-slate-900">{{ payment.invoice_reference }}</td>
                                            <td class="px-4 py-3">
                                                <p class="font-medium text-slate-900">{{ payment.customer_name }}</p>
                                                <p class="text-xs text-slate-500">{{ payment.customer_email }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">{{ payment.items_summary }}</td>
                                            <td class="px-4 py-3 font-semibold text-slate-900">{{ payment.amount }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ payment.payment_method }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ payment.paid_at }}</td>
                                            <td class="px-4 py-3">
                                                <span class="max-w-[12rem] break-all font-mono text-xs text-slate-500">
                                                    {{ payment.transaction_id || '—' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="recentPayments.length === 0">
                                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                                                لا توجد مدفوعات ناجحة حتى الآن.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
