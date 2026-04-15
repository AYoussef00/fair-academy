<script setup lang="ts">
import { ref } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    src: string | null | undefined;
    alt: string;
    class?: string;
}>();

const hasError = ref(false);
const imageClass = () => cn('bg-[#E5E5EA] object-cover', props.class);
const fallbackClass = () => cn('flex items-center justify-center bg-[#E5E5EA] text-[#8E8E93]', props.class);
</script>

<template>
    <img
        v-if="src && !hasError"
        :src="src"
        :alt="alt"
        :class="imageClass()"
        @error="hasError = true"
    />
    <div v-else :class="fallbackClass()">
        <span class="text-sm">No image</span>
    </div>
</template>
