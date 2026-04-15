<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { BookOpen, GraduationCap, DollarSign } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدرب', href: '/instructor/dashboard' },
];

const page = usePage();
const userName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مدرب';

const props = withDefaults(
    defineProps<{
        stats?: {
            coursesCount: number;
            studentsCount: number;
            totalRevenue: number;
        };
    }>(),
    {
        stats: () => ({
            coursesCount: 0,
            studentsCount: 0,
            totalRevenue: 0,
        }),
    }
);
</script>

<template>
    <Head title="لوحة المدرب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="mx-auto max-w-7xl px-6 py-8">
                <div class="mb-8">
                    <h1 class="mb-2 text-4xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                        أهلاً، {{ userName }}
                    </h1>
                    <p class="text-lg text-[#8E8E93]">
                        لوحة تحكم المدرب – إدارة دوراتك وطلابك
                    </p>
                </div>

                <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Card class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 dark:bg-violet-900/40">
                                <BookOpen :size="28" class="text-violet-600 dark:text-violet-400" />
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                                    {{ props.stats.coursesCount }}
                                </p>
                                <p class="text-sm text-[#8E8E93]">دوراتي</p>
                            </div>
                        </div>
                    </Card>
                    <Card class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 dark:bg-emerald-900/40">
                                <GraduationCap :size="28" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                                    {{ props.stats.studentsCount }}
                                </p>
                                <p class="text-sm text-[#8E8E93]">الطلاب</p>
                            </div>
                        </div>
                    </Card>
                    <Card class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-900/40">
                                <DollarSign :size="28" class="text-amber-600 dark:text-amber-400" />
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                                    {{ props.stats.totalRevenue }}
                                </p>
                                <p class="text-sm text-[#8E8E93]">الإيرادات</p>
                            </div>
                        </div>
                    </Card>
                </div>

                <Card class="p-8">
                    <h2 class="text-xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                        دوراتي
                    </h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        ستظهر هنا الدورات التي تدرّسها. يمكنك إضافة دورات جديدة من لوحة الإدارة.
                    </p>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
