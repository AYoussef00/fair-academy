<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    Banknote,
    BookOpen,
    Calendar,
    CheckCircle2,
    CreditCard,
    Download,
    FileText,
    LayoutDashboard,
    MessageSquare,
    Receipt,
    TriangleAlert,
    User,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const userNameFromPage = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText },
    { title: 'الحضور والغياب', href: '#', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt, active: true },
    { title: 'الملف الشخصي', href: '#', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'المدفوعات والفواتير', href: '/student/payments' },
];

const props = defineProps<{
    invoices: Array<{
        id: number;
        reference: string;
        course_title: string;
        amount: string;
        due_date_human: string;
        status: 'unpaid' | 'paid' | 'overdue';
        payment_method: string | null;
        paid_at: string | null;
        receipt_url: string | null;
    }>;
    userName: string;
}>();

function payNow(invoiceId: number) {
    // مبدئياً نوجّه الطالب إلى صفحة الدفع العامة، لاحقاً يمكن ربطها بفاتورة محددة
    router.visit('/checkout');
}
</script>

<template>
    <Head title="المدفوعات والفواتير – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside
                    class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block"
                >
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ userNameFromPage }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">طالب</p>
                        </div>
                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="(item, index) in navItems" :key="index">
                                    <Link
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
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <!-- المحتوى الرئيسي -->
                <main class="min-w-0 flex-1">
                    <div class="w-full px-6 py-6 space-y-6">
                        <!-- العنوان -->
                        <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">💰 المدفوعات والفواتير</h1>
                                <p class="mt-1 text-sm text-slate-500">
                                    من هنا تتابع الفواتير المستحقة وحالة الدفع، وتقوم بالدفع أونلاين وتحمل إيصالات مدفوعاتك في أي وقت.
                                </p>
                            </div>
                            <div class="rounded-xl bg-white px-4 py-2 text-xs text-slate-500 shadow-sm dark:bg-slate-800 dark:text-slate-300">
                                <span class="font-semibold text-[#ed9134]">
                                    {{ props.invoices.filter(i => i.status === 'unpaid' || i.status === 'overdue').length }}
                                </span>
                                فاتورة بحاجة إلى دفع
                            </div>
                        </div>

                        <!-- تنبيه للفواتير المتأخرة -->
                        <div
                            v-if="props.invoices.some(i => i.status === 'overdue')"
                            class="flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 dark:border-red-500/40 dark:bg-red-900/30 dark:text-red-100"
                        >
                            <TriangleAlert class="mt-0.5 h-4 w-4 shrink-0" />
                            <p>
                                لديك فواتير <span class="font-semibold">متأخرة عن السداد</span>. يُرجى إتمام الدفع في أسرع وقت للحفاظ على تفعيل
                                اشتراكك في البرامج والدورات.
                            </p>
                        </div>

                        <!-- جدول الفواتير -->
                        <Card class="overflow-hidden border border-slate-200 bg-white p-0 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-right dark:divide-slate-700">
                                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 dark:bg-slate-900/30">
                                        <tr>
                                            <th class="px-4 py-3">رقم الفاتورة</th>
                                            <th class="px-4 py-3">البرنامج / الدورة</th>
                                            <th class="px-4 py-3">المبلغ</th>
                                            <th class="px-4 py-3">تاريخ الاستحقاق</th>
                                            <th class="px-4 py-3">الحالة</th>
                                            <th class="px-4 py-3">طريقة الدفع</th>
                                            <th class="px-4 py-3">إيصال الدفع</th>
                                            <th class="px-4 py-3">الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs dark:divide-slate-700">
                                        <tr
                                            v-for="invoice in invoices"
                                            :key="invoice.id"
                                            class="hover:bg-slate-50/60 dark:hover:bg-slate-800/60"
                                        >
                                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-800 dark:text-slate-100">
                                                {{ invoice.reference }}
                                            </td>
                                            <td class="max-w-xs px-4 py-3 text-slate-700 dark:text-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <BookOpen class="h-3.5 w-3.5 text-[#ed9134]" />
                                                    <span class="line-clamp-2">{{ invoice.course_title }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-800 dark:text-slate-100">
                                                {{ invoice.amount }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">
                                                {{ invoice.due_date_human }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                                    :class="invoice.status === 'paid'
                                                        ? 'bg-emerald-50 text-emerald-700'
                                                        : invoice.status === 'overdue'
                                                            ? 'bg-red-50 text-red-700'
                                                            : 'bg-amber-50 text-amber-700'"
                                                >
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full"
                                                        :class="invoice.status === 'paid'
                                                            ? 'bg-emerald-500'
                                                            : invoice.status === 'overdue'
                                                                ? 'bg-red-500'
                                                                : 'bg-amber-500'"
                                                    />
                                                    <span v-if="invoice.status === 'paid'">مدفوع ✅</span>
                                                    <span v-else-if="invoice.status === 'overdue'">متأخر ❌</span>
                                                    <span v-else>لم يُدفع ❌</span>
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-[11px] text-slate-600 dark:text-slate-300">
                                                <span v-if="invoice.payment_method">
                                                    {{ invoice.payment_method }}
                                                    <span v-if="invoice.paid_at" class="block text-[10px] text-slate-400">
                                                        في: {{ invoice.paid_at }}
                                                    </span>
                                                </span>
                                                <span v-else class="text-slate-400">لم يتم الدفع بعد</span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <button
                                                    v-if="invoice.status === 'paid' && invoice.receipt_url"
                                                    type="button"
                                                    class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
                                                >
                                                    <Download class="h-3.5 w-3.5" />
                                                    تحميل الإيصال (PDF)
                                                </button>
                                                <span
                                                    v-else
                                                    class="text-[11px] text-slate-400"
                                                >
                                                    غير متاح
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <button
                                                    v-if="invoice.status !== 'paid'"
                                                    type="button"
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-[#ed9134] px-3 py-1.5 text-[11px] font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                                                    @click="payNow(invoice.id)"
                                                >
                                                    <CreditCard class="h-3.5 w-3.5" />
                                                    ادفع الآن
                                                </button>
                                                <span
                                                    v-else
                                                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600"
                                                >
                                                    <CheckCircle2 class="h-3.5 w-3.5" />
                                                    مدفوع بالكامل
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- ملاحظة في الأسفل -->
                            <div class="border-t border-slate-100 bg-slate-50 px-4 py-3 text-[11px] text-slate-500 dark:border-slate-700 dark:bg-slate-900/40">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-1">
                                        <CreditCard class="h-3.5 w-3.5 text-[#ed9134]" />
                                        يمكنك الدفع أونلاين باستخدام البطاقة البنكية أو التحويل البنكي حسب طرق الدفع المفعّلة.
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <Banknote class="h-3.5 w-3.5 text-slate-400" />
                                        جميع المدفوعات تظهر هنا مع إمكانية تحميل إيصال الدفع بصيغة PDF.
                                    </span>
                                </div>
                            </div>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>

