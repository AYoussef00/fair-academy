<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Users, BookOpen, FolderOpen } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
];

const props = withDefaults(
    defineProps<{
        stats?: {
            usersCount: number;
            coursesCount: number;
            programsCount: number;
        };
    }>(),
    {
        stats: () => ({
            usersCount: 0,
            coursesCount: 0,
            programsCount: 0,
        }),
    }
);
</script>

<template>
    <Head title="لوحة المدير" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="mx-auto max-w-7xl px-6 py-8">
                <div class="mb-8">
                    <h1 class="mb-2 text-4xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                        لوحة المدير
                    </h1>
                    <p class="text-lg text-[#8E8E93]">
                        إدارة المنصة والمستخدمين والبرامج والدورات
                    </p>
                </div>

                <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Card class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-100 dark:bg-sky-900/40">
                                <Users :size="28" class="text-sky-600 dark:text-sky-400" />
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                                    {{ props.stats.usersCount }}
                                </p>
                                <p class="text-sm text-[#8E8E93]">المستخدمون</p>
                            </div>
                        </div>
                    </Card>
                    <Card class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 dark:bg-violet-900/40">
                                <BookOpen :size="28" class="text-violet-600 dark:text-violet-400" />
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                                    {{ props.stats.coursesCount }}
                                </p>
                                <p class="text-sm text-[#8E8E93]">الدورات</p>
                            </div>
                        </div>
                    </Card>
                    <Card class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 dark:bg-emerald-900/40">
                                <FolderOpen :size="28" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                                    {{ props.stats.programsCount }}
                                </p>
                                <p class="text-sm text-[#8E8E93]">البرامج</p>
                            </div>
                        </div>
                    </Card>
                </div>

                <Card class="p-8">
                    <h2 class="text-xl font-bold text-[#1C1C1E] dark:text-[#F8F9FA]">
                        إدارة المنصة
                    </h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        إدارة المستخدمين والأدوار، البرامج، الدورات، والتقارير من هنا (يمكن توسيعها لاحقاً).
                    </p>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
