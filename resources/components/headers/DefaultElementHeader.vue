<template>
    <header
        ref="container"
        :style="backgroundStyle"
        class="relative mt-auto flex h-full flex-col justify-end gap-5 px-2 py-2 text-white"
    >
        <div class="animate-move sticky top-4">
            <div
                v-if="tags.length"
                class="flex gap-1"
            >
                <tag-pill v-for="tag in tags">
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
        <div
            v-if="genres.length"
            class="animate-hide line-clamp-1 flex gap-1 text-sm opacity-50"
        >
            <span
                v-for="(genre, index) in genres"
                class="flex items-center gap-1 whitespace-nowrap"
            >
                <point-icon v-if="index !== 0" />
                {{ genre }}
            </span>
        </div>
        <div
            v-if="detail?.length || $slots.detail"
            class="animate-hide sticky"
        >
            <slot name="detail">
                <p
                    v-if="detail?.length"
                    class="text-sm opacity-75"
                >
                    {{ detail }}
                </p>
            </slot>
        </div>
        <div class="sticky top-32">
            <div
                class="animate-hide flex h-[48px] items-center justify-between bg-stone-500 px-3"
            >
                achievements
            </div>
            <progress-bar-circle
                :percent="80"
                class="absolute bottom-6 right-3 translate-y-1/2 opacity-100"
            />
        </div>
    </header>
</template>

<script lang="ts" setup>
import TagPill from '@/components/TagPill.vue'
import PointIcon from '@/components/icons/PointIcon.vue'
import { computed, inject, onMounted, ref } from 'vue'
import ProgressBarCircle from '@/components/ProgressBarCircle.vue'
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
    detail: {
        type: String,
        default: '',
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
    return {
        background: props.background
            ? `url(${props.background}) no-repeat center center/cover`
            : 'linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), ' +
              'linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), ' +
              '#404040',
    }
})

onMounted(() => {
    const hideElements = container.value?.querySelectorAll('.animate-hide')
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
    if (moveElements)
        scroll(
            animate(moveElements, {
                paddingLeft: ['0', '2rem'],
                width: ['100%', 'calc(100% - 50px)'],
            }),
            {
                offset: ['start start', `${scrollHeight / 4}px`],
            }
        )
})
</script>

<style scoped></style>
