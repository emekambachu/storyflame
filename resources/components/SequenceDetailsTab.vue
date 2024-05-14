<template>
    <div class="flex flex-col px-4">
        <div class="py-5">
            <p class="text-sm text-zinc-800 font-normal">
                {{ data.kickstart }}
            </p>
        </div>
        <div class="py-2 rounded-lg">
            <div class="py-5 flex flex-col gap-3 px-2 rounded-lg bg-slate-200">
                <h5 class="text-base text-zinc-800 font-bold">
                    Who drives the scene
                </h5>
                <p class="text-xs text-zinc-700 font-normal">
                    {{ data.drives_scene }}
                </p>
            </div>
        </div>

        <div class="py-5 flex flex-col gap-3">
            <h5 class="text-base text-zinc-800 font-bold">Sequence Goals</h5>
            <div class="flex flex-col gap-2">
                <sequence-goal-card
                    v-for="(goal, goalID) in data.goals"
                    :key="goalID"
                    :goal="goal"
                />
            </div>
        </div>
        <div class="py-5 flex flex-col gap-3">
            <h5 class="font-bold text-sm text-slate-500">Source of conflict</h5>
            <ul>
                <li
                    v-for="(reason, reasonID) in data.conflict_sources"
                    :key="reasonID"
                    class="font-normal text-xs text-slate-500 flex items-start gap-2"
                >
                    <point-icon class="w-3 h-3 mt-1" />
                    {{ reason }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'

import SequenceGoalCard from '@/components/cards/SequenceGoalCard.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
