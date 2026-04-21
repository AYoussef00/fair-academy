<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { AlertTriangle } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';

defineProps<{
    message?: string;
}>();
</script>

<template>
    <Head title="فشل الدفع – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-screen flex-col bg-white">
        <SiteHeader />
        <main class="mx-auto flex flex-1 flex-col items-center justify-center px-4 py-16">
            <div class="flex max-w-md flex-col items-center rounded-2xl border border-slate-200 bg-slate-50/50 p-10 text-center shadow-sm">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <AlertTriangle class="h-12 w-12" />
                </div>
                <h1 class="mt-6 text-2xl font-bold text-slate-900">
                    لم تكتمل عملية الدفع
                </h1>
                <p class="mt-2 text-slate-600">
                    {{ message || 'لم يتم تأكيد الدفع من Noon. يمكنك المحاولة مرة أخرى من السلة.' }}
                </p>
                <Link
                    href="/cart"
                    class="mt-8 inline-flex items-center gap-2 rounded-xl bg-violet-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg transition hover:bg-violet-700"
                >
                    العودة إلى السلة
                </Link>
            </div>
        </main>
    </div>
</template>
