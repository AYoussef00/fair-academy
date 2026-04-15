<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="header" class="flex min-h-screen w-full flex-col">
        <AppHeader :breadcrumbs="breadcrumbs" />
        <AppContent class="flex-1 overflow-x-hidden">
            <slot />
        </AppContent>
    </AppShell>
</template>
