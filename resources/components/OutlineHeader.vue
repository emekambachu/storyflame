<template>
    <div
        ref="container"
        :class="outline?.image?.path ? 'pb-4' : ''"
        :style="`background: ${
            outline?.image?.path
                ? `linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url(${outline.image.path}) lightgray 50% / cover no-repeat;`
                : 'transparent'
        }`"
        class="flex h-fit w-full flex-col justify-end gap-2 px-4 pt-14"
    >
        <span
            class="w-fit rounded bg-stone-600 p-1 text-[10px] font-normal text-stone-300"
        >
            {{ outline?.format }}
        </span>

        <div class="flex w-full items-center justify-between">
            <h1
                :class="outline?.image?.path ? 'text-white' : 'text-black'"
                class="text-xl font-bold"
            >
                {{ outline?.title }}
            </h1>
        </div>

        <p
            v-if="outline?.story || outline?.type"
            class="flex items-center gap-1.5 text-sm font-normal text-stone-400"
        >
            {{ outline?.story }}
            <point-icon v-if="outline?.story && outline?.type" />
            {{ outline?.type }}
        </p>

        <div
            class="flex w-full items-center justify-between rounded-lg px-3 py-2"
            style="background: rgba(40, 37, 36, 0.8)"
        >
            <div class="flex w-full items-center gap-2">
                <achievements-list-card :achievements="outline?.achievements" />
                <span class="text-[8px] font-bold text-stone-500">
                    {{
                        `${completedAchievements?.length}/${outline?.achievements?.length ?? 0}`
                    }}
                </span>
            </div>
            <flame-icon
                v-if="outline?.progress"
                :progress="outline?.progress"
                class="!w-8 w-full rounded-full bg-white"
            />
        </div>
    </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue'
import PointIcon from '@/components/icons/PointIcon.vue'
import AchievementsListCard from '@/components/cards/AchievementsListCard.vue'
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import { propsValues } from 'troisjs'

const props = defineProps({
    outline: {
        type: Object,
        required: true,
    },
})

const completedAchievements = computed(() => {
    if (props.outline.achievements) {
        return props.outline.achievements.filter(
            (achievement: Object) => achievement?.progress == 100
        )
    } else {
        return []
    }
})
</script>
