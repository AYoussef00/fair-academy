<script setup lang="ts">
defineProps<{
    progress: number;
    showLabel?: boolean;
}>();
</script>

<template>
    <div class="w-full">
        <div
            class="h-2 w-full overflow-hidden rounded-full bg-[#E5E5EA]"
            role="progressbar"
            :aria-valuenow="progress"
            aria-valuemin="0"
            aria-valuemax="100"
        >
            <div
                class="h-full rounded-full bg-[#0A84FF] transition-all duration-300"
                :style="{ width: `${Math.min(100, Math.max(0, progress))}%` }"
            />
        </div>
        <p v-if="showLabel" class="mt-1 text-sm text-[#8E8E93]">
            {{ Math.round(progress) }}% مكتمل
        </p>
    </div>
</template>
