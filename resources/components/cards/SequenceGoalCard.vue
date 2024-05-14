<template>
    <div class="w-full flex items-center gap-3">
        <div
            v-if="goal.progress.status == 'Finished'"
            class="w-5 h-5 shrink-0 rounded-full bg-red-600 flex items-center justify-center"
        >
            <check-icon class="text-pure-white" />
        </div>
        <div
            v-else
            class="w-6 h-6 shrink-0 rounded-full bg-red-100 flex items-center justify-center"
        >
            <p class="flex items-center justify-center text-center">
                <span class="text-xs text-zinc-700 font-normal">
                    {{ goal.progress.done }}
                </span>
                <span class="text-[8px] text-slate-400 font-normal">
                    /{{ goal.progress.total_amount }}
                </span>
            </p>
        </div>
        <p class="text-zinc-700 text-xs font-normal max-w-64">
            {{ goal.description }}
        </p>
    </div>
</template>

<script setup lang="ts">
import CheckIcon from '@/components/icons/CheckIcon.vue'

const props = defineProps({
    goal: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
