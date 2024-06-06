<template>
    <router-link
        v-slot="{ navigate }"
        :to="{ name: 'story', params: { id: story.id } }"
        custom
    >
        <button
            class="flex w-full flex-col items-start gap-2.5 rounded-lg bg-slate-200 p-2.5"
            @click="navigate"
        >
            <div class="flex gap-2">
                <image-component
                    :src="story?.image?.path"
                    alt="story"
                    class="h-20 w-20 rounded-lg object-cover"
                />

                <div class="flex w-full flex-col">
                    <h4 class="text-base font-bold text-neutral-700">
                        {{ truncateTitle(story.name, 18) }}
                    </h4>

                    <p
                        v-if="story?.type"
                        class="mt-3.5 text-sm font-normal text-neutral-700"
                    >
                        {{ story.type }}
                    </p>

                    <div
                        v-if="story?.genres?.length"
                        class="flex max-w-full items-center gap-2 overflow-auto text-black opacity-50"
                    >
                        <template
                            v-for="(genre, genreID) in story.genres"
                            :key="genreID"
                        >
                            <point-icon v-if="genreID !== 0" />

                            <p class="text-sm font-normal">
                                {{ genre }}
                            </p>
                        </template>
                    </div>
                </div>
            </div>

            <p
                v-if="story?.description"
                class="text-sm font-normal text-neutral-700"
            >
                {{ truncateTitle(story.description, 145) }}
            </p>
        </button>
    </router-link>
</template>

<script lang="ts" setup>
import PointIcon from '@/components/icons/PointIcon.vue'

import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps({
    story: {
        type: Object,
        required: true,
    },
})

function truncateTitle(title: string | undefined, maxLength: number): string {
    if (!title) return ''
    return title.length > maxLength
        ? title.substring(0, maxLength - 3) + '...'
        : title
}
</script>

<style scoped></style>
