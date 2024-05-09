<template>
    <div class="flex flex-col w-full gap-8">
        <title-section title="About">
            <p class="text-neutral-950 text-sm font-normal px-4">
                {{ user.data?.bio }}
            </p>
        </title-section>

        <title-section v-if="user.data?.writing_medium?.length" title="Writing Goals">
            <items-list
                v-slot="{ item }"
                :items="user.data?.writing_medium"
                direction="col"
            >
                <div
                    class="text-neutral-950 text-sm font-normal flex items-center gap-2 pl-2"
                >
                    <point-icon />
                    {{ item }}
                </div>
            </items-list>
        </title-section>

        <title-section v-if="user.data?.media?.length" title="Inspired by">
            <items-list
                v-slot="{ item }"
                :items="user.data?.media"
            >
                <movie-card :media="item" />
            </items-list>
        </title-section>

        <title-section v-if="user.data?.characters?.length" title="Favorite characters">
            <items-list
                v-slot="{ item }"
                :items="user.data?.characters"
            >
                <p
                    class="text-slate-600 whitespace-nowrap text-xs font-normal px-3 py-2 rounded-full"
                    style="background-color: rgba(96, 159, 255, 0.1)"
                >
                    {{ item }}
                </p>
            </items-list>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Your Stories"
                    @see-all="activeTab = 'stories'"
                />
            </template>
            <div
                v-if="user.stories?.length"
                class="flex flex-col gap-4 w-full"
            >
                <story-card
                    v-for="(story, storyID) in user.stories"
                    :key="storyID"
                    :story="story"
                />
            </div>
            <div
                v-else
                class="px-4 w-full"
            >
                <div class="p-1 bg-zinc-200 rounded-lg w-full">
                    <div
                        class="flex flex-col items-center gap-2 rounded-lg w-full p-4 border border-dashed border-gray-400"
                    >
                        <div
                            class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center bg-gray-400"
                        >
                            <plus-icon class="text-white" />
                        </div>
                        <span class="text-sm text-gray-400 font-normal">
                            Start building your first story
                        </span>
                    </div>
                </div>
            </div>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Your Achievements"
                    @see-all="activeTab = 'achievements'"
                />
            </template>
            <items-list
                class="gap-8"
                v-slot="{ item }"
                :items="user.achievements.filter((item)=>item.progress)"
            >
                <achievement-card class="h-full" :item="item" />
            </items-list>
        </title-section>
    </div>
</template>

<script lang="ts" setup>
import PointIcon from '@/components/icons/PointIcon.vue'
import { inject, PropType } from 'vue'
import User from '@/types/user'
import TitleSection from '@/components/TitleSection.vue'
import ItemsList from '@/components/ItemsList.vue'
import MovieCard from '@/components/cards/MovieCard.vue'
import StoryCard from '@/components/cards/StoryCard.vue'
import AchievementCard from '@/components/cards/AchievementCard.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
})

const activeTab = inject<string>('activeTab')
</script>
