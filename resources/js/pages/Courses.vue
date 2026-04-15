<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import { courses, dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الكورسات', href: courses() },
];
</script>

<template>
    <Head title="الدورات – أكاديمية فايرير للتدريب والتعليم" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 py-8">
                <h1 class="mb-2 text-3xl font-bold text-slate-900">الكورسات</h1>
                <p class="mb-8 text-slate-600">
                    تصفّح وأدر الكورسات المسجّل فيها.
                </p>
                <Card class="flex flex-col items-center justify-center gap-4 py-16">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-100">
                        <BookOpen class="h-8 w-8 text-violet-600" />
                    </div>
                    <p class="text-slate-600">ستظهر كورساتك هنا.</p>
                    <Link
                        :href="dashboard()"
                        class="text-sm font-medium text-violet-600 hover:text-violet-700"
                    >
                        العودة إلى لوحة التحكم
                    </Link>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
