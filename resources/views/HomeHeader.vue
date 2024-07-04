<template>
    <div class="flex w-full flex-col gap-3 bg-neutral-950 px-4 py-4">
        <div class="flex w-full items-center justify-between">
            <logo-icon />

            <router-link
                :to="{
                    name: 'profile',
                }"
                class="h-8 w-8 shrink-0 rounded-full bg-white"
            ></router-link>
        </div>

<!--        <p class="text-sm font-medium uppercase text-slate-400">smth</p>-->

        <div v-if="user" class="flex items-center justify-between gap-3">
            <achievement-summary-card
                title="Earned"
                :value="user.completed_achievements"
            card-class="flex flex-col gap-2 p-2 rounded-lg bg-neutral-800 w-full max-w-28"
                value-class="text-sm text-pure-white font-bold"
            />
            <achievement-summary-card
                title="In Progress"
                :value="user.progress_achievements"
                card-class="flex flex-col gap-2 p-2 rounded-lg bg-neutral-800 w-full max-w-28"
                value-class="text-sm text-pure-white font-bold"
            />
            <achievement-summary-card
                title="Up Next"
                :value="user.next_achievements"
                card-class="flex flex-col gap-2 p-2 rounded-lg bg-neutral-800 w-full max-w-28"
                value-class="text-sm text-pure-white font-bold"
            />
        </div>
    </div>
</template>
<script lang="ts" setup>
import LogoIcon from '@/components/icons/LogoIcon.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'
import { useUser } from '@/composables/query/user'

const { data: user } = useUser()
</script>
