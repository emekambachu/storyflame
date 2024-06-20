<template>
    <div
        ref="container"
        :class="story?.image?.path ? 'pb-4' : ''"
        :style="`background: ${
            story?.image?.path
                ? `linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url(${story.image.path}) lightgray 50% / cover no-repeat;`
                : 'transparent'
        }`"
        class="flex h-full w-full flex-col justify-end gap-1.5 px-4 pt-16"
    >
        <!--        <div class="flex flex-col gap-1.5 px-4">-->
        <span
            :class="story?.image?.path ? 'text-white' : 'text-black'"
            class="animate-resize animate-move sticky top-4 text-sm font-normal opacity-70"
        >
            {{ story?.format }}
        </span>

        <div
            class="animate-move sticky top-10 flex w-full items-center justify-between"
        >
            <h1
                :class="story?.image?.path ? 'text-white' : 'text-black'"
                class="text-3xl font-bold"
            >
                {{ story?.name }}
            </h1>

            <div class="h-10 w-10 shrink-0">
                <progress-bar-circle
                    v-if="story"
                    :percent="story?.percent"
                    class="w-full"
                />
            </div>
        </div>

        <div class="animate-hide flex items-center gap-2">
            <span
                v-for="(genre, genreID) in story?.genres"
                :key="genreID"
                :class="story?.image?.path ? 'text-white' : 'text-black'"
                class="flex items-center gap-1.5 text-sm font-normal opacity-40"
            >
                <span v-if="genreID !== 0"><point-icon /></span>
                {{ genre }}
            </span>
        </div>

        <p
            :class="story?.image?.path ? 'text-white' : 'text-black'"
            class="animate-hide text-sm opacity-70"
        >
            {{ story?.description }}
        </p>
        <div
            class="flex w-full items-center justify-between rounded-lg px-3 py-2"
            style="background: rgba(40, 37, 36, 0.8)"
        >
            <div class="flex w-full items-center gap-2">
                <achievements-list-card :achievements="story?.achievements" />
                <span class="text-[8px] font-bold text-stone-500">
                    {{
                        `${completedAchievements?.length}/${story?.achievements?.length ?? 0}`
                    }}
                </span>
            </div>
            <flame-icon
                v-if="story?.progress"
                :progress="story?.progress"
                class="!w-8 w-full rounded-full bg-white"
            />
        </div>
    </div>
</template>
<script lang="ts" setup>
import { ref, computed, onMounted, PropType } from 'vue'
import { Story } from '@/types/story'
import { animate, scroll } from 'motion'
import PointIcon from '@/components/icons/PointIcon.vue'
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import AchievementsListCard from '@/components/cards/AchievementsListCard.vue'

const props = defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})

const container = ref<HTMLDivElement | null>(null)

onMounted(() => {
    const hideElements = container.value?.querySelectorAll('.animate-hide')
    const moveElements = container.value?.querySelectorAll('.animate-move')
    const resizeElements = container.value?.querySelectorAll('.animate-resize')

    scroll(
        animate(hideElements, {
            opacity: [1, 0],
        }),
        {
            offset: ['start start', '100px'],
        }
    )

    scroll(
        animate(moveElements, {
            translateY: [0, 10],
        }),
        {
            offset: ['start start', '300px'],
        }
    )

    scroll(
        animate(resizeElements, {
            width: ['fit-content', 'fit-content'],
            translateX: ['0%', '-50%'],
            left: ['0%', '50%'],
        }),
        {
            offset: ['start start', '300px'],
        }
    )
})

const completedAchievements = computed(() => {
    if (props.story.achievements) {
        return props.story.achievements.filter(
            (achievement: Object) => achievement?.progress == 100
        )
    } else {
        return []
    }
})
</script>
