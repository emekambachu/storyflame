<template>
    <div class="flex w-full flex-col gap-8">
        <div class="flex flex-col gap-6 px-4">
            <h4
                v-if="!stories?.length"
                class="text-lg font-bold text-black"
            >
                Stories
            </h4>

            <div
                v-if="stories?.length"
                class="flex w-full flex-col gap-4"
            >
                <story-card
                    v-for="(story, storyID) in stories"
                    :key="storyID"
                    :story="story"
                />
            </div>

            <div
                v-else
                class="mt-[20%] flex w-full flex-col items-center gap-2"
            >
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-400"
                >
                    <plus-icon class="text-white" />
                </div>
                <span class="text-sm font-normal text-gray-400">
                    Start building your first story
                </span>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import StoryCard from '@/components/cards/StoryCard.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'
import { useQuery } from '@tanstack/vue-query'
import { getStories } from '@/utils/endpoints'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})

const { data: stories } = useQuery({
    queryKey: ['stories'],
    queryFn: () => getStories(),
    select(data) {
        return data.data
    },
})

function truncateTitle(title: string, maxLength: number): string {
    return title.length > maxLength
        ? title.substring(0, maxLength - 3) + '...'
        : title
}
</script>

<style scoped></style>
