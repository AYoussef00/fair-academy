<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2 } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';
import { dashboard } from '@/routes';

defineProps<{
    message?: string;
}>();
</script>

<template>
    <Head title="تم الدفع بنجاح – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-screen flex-col bg-white">
        <SiteHeader />
        <main class="mx-auto flex flex-1 flex-col items-center justify-center px-4 py-16">
            <div class="flex max-w-md flex-col items-center rounded-2xl border border-slate-200 bg-slate-50/50 p-10 text-center shadow-sm">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <CheckCircle2 class="h-12 w-12" />
                </div>
                <h1 class="mt-6 text-2xl font-bold text-slate-900">
                    تم الدفع بنجاح
                </h1>
                <p class="mt-2 text-slate-600">
                    {{ message || 'تم تسجيلك في الدورات بنجاح. يمكنك البدء بالتعلّم من لوحة التحكم.' }}
                </p>
                <Link
                    :href="dashboard()"
                    class="mt-8 inline-flex items-center gap-2 rounded-xl bg-violet-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg transition hover:bg-violet-700"
                >
                    الذهاب إلى لوحة التحكم
                </Link>
            </div>
        </main>
    </div>
</template>
