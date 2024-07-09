<template>
    <header
        ref="container"
        :style="backgroundStyle"
        class="relative mt-auto flex h-full flex-col justify-end bg-cover bg-center bg-no-repeat px-4 py-[76px] pb-5 text-white"
    >
        <div
            class="animate-move sticky top-6 mb-2 flex w-full items-center justify-between"
        >
            <div>
                <div
                    v-if="tags.length"
                    class="mb-2 flex gap-1"
                >
                    <tag-pill
                        v-for="(tag, tagID) in tags"
                        :key="tagID"
                    >
                        {{ tag }}
                    </tag-pill>
                </div>
                <slot name="title">
                    <h1
                        v-if="title.length"
                        class="line-clamp-1 font-fjalla text-[32px]"
                    >
                        {{ title }}
                    </h1>
                </slot>
            </div>

            <flame-icon
                v-if="progress"
                :progress="progress"
                class="animate-show !w-8 w-full rounded-full bg-white"
            />
        </div>
        <div
            v-if="genres.length"
            class="animate-hide mb-3 line-clamp-1 flex min-h-4 gap-1 text-sm text-stone-400"
        >
            <span
                v-for="(genre, genreID) in genres"
                :key="genreID"
                class="flex items-center gap-1 whitespace-nowrap"
            >
                <point-icon v-if="genreID !== 0" />
                {{ genre }}
            </span>
        </div>
        <div class="animate-hide mb-3">
            <slot name="detail">
                <p
                    v-if="detail.length"
                    class="text-sm text-stone-300"
                >
                    {{ detail }}
                </p>
            </slot>
        </div>
        <div
            class="animate-hide flex w-full items-center justify-between rounded-lg px-3 py-2"
            style="background: rgba(40, 37, 36, 0.8)"
        >
            <div
                v-if="achievements?.length"
                class="flex w-full items-center gap-2"
            >
                <achievements-list-card :achievements="achievements" />
                <span class="text-[8px] font-bold text-stone-500">
                    {{
                        `${completedAchievements?.length}/${achievements?.length ?? 0}`
                    }}
                </span>
            </div>
            <flame-icon
                v-if="progress"
                :progress="progress"
                class="!w-8 w-full rounded-full bg-white"
            />
        </div>

        <!-- <div
                class="animate-hide flex h-[48px] items-center justify-between bg-stone-500 px-3"
            >
                achievements
            </div>
            <progress-bar-circle
                :percent="progress"
                class="absolute bottom-6 right-3 translate-y-1/2 opacity-100"
            /> -->
    </header>
</template>

<script lang="ts" setup>
import TagPill from '@/components/TagPill.vue'
import PointIcon from '@/components/icons/PointIcon.vue'
import AchievementsListCard from '@/components/cards/AchievementsListCard.vue'
import { computed, inject, onMounted, ref } from 'vue'
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import { animate, scroll } from 'motion'

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    tags: {
        type: Array,
        default: () => [],
    },
    genres: {
        type: Array,
        default: () => [],
    },
    progress: {
        type: Number,
        default: 80,
    },
    detail: {
        type: String,
        default: '',
    },
    achievements: {
        type: Array,
        default: () => [],
    },
    background: {
        type: String,
        default: () => undefined,
    },
    height: {
        type: String,
        default: () => undefined,
    },
})

const container = ref<HTMLElement | null>(null)
const scrollHeight = inject('scrollHeight') as number

const backgroundStyle = computed(() => {
    if (props.background) {
        return {
            backgroundImage: `linear-gradient(0deg, rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url(${props.background})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
        }
    } else {
        return {
            background:
                'linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), ' +
                'linear-gradient(0deg, rgba(0, 0, 0, 0.30), rgba(0, 0, 0, 0.30)), ' +
                '#404040',
        }
    }
})

onMounted(() => {
    const hideElements = container.value?.querySelectorAll('.animate-hide')
    const showElements = container.value?.querySelectorAll('.animate-show')
    const moveElements = container.value?.querySelectorAll('.animate-move')

    if (hideElements) {
        scroll(
            animate(hideElements, {
                opacity: [1, 0],
            }),
            {
                offset: ['start start', `${scrollHeight / 2}px`],
            }
        )
        hideElements.forEach((element) => {
            const height = element.clientHeight
            scroll(
                animate(element, {
                    height: [`${height}px`, '0'],
                }),
                {
                    offset: [`${scrollHeight / 2}px`, `${scrollHeight}px`],
                }
            )
        })
    }

    if (showElements) {
        scroll(
            animate(showElements, {
                opacity: [0, 1],
            }),
            {
                offset: ['start start', `${scrollHeight / 2}px`],
            }
        )
        showElements.forEach((element) => {
            scroll(
                animate(element, {
                    opacity: [0, 1],
                }),
                {
                    offset: [`${scrollHeight / 2}px`, `${scrollHeight}px`],
                }
            )
        })
    }

    if (moveElements) {
        scroll(
            animate(moveElements, {
                paddingLeft: ['0', '2rem'],
            }),
            {
                offset: ['start start', `${scrollHeight / 4}px`],
            }
        )
    }
})
const completedAchievements = computed(() => {
    if (props?.achievements) {
        return props.achievements.filter(
            (achievement: Object) => achievement?.progress == 100
        )
    } else {
        return []
    }
})
</script>

<style scoped></style>
