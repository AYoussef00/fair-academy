<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div>
        <SiteHeader />
        <div
            v-if="props.breadcrumbs.length > 1"
            class="flex w-full border-b border-slate-200 bg-white"
        >
            <Breadcrumbs :breadcrumbs="breadcrumbs" />
        </div>
    </div>
</template>
