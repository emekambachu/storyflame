<template>
    <div class="flex w-full flex-col gap-8">
        <title-section
            v-if="user?.bio"
            title="About"
        >
            <p class="px-4 text-sm font-normal text-neutral-950">
                {{ user?.bio }}
            </p>
        </title-section>
        <title-section
            v-if="user?.bio"
            title="Writing Goals"
        >
            <p class="px-4 text-sm font-normal text-neutral-950">
                {{ user?.writing_goals }}
            </p>
        </title-section>

        <!--        <title-section v-if="user.data?.writing_medium?.length" title="Writing Goals">-->
        <!--            <items-list-->
        <!--                v-slot="{ item }"-->
        <!--                :items="user.data?.writing_medium"-->
        <!--                direction="col"-->
        <!--            >-->
        <!--                <div-->
        <!--                    class="text-neutral-950 text-sm font-normal flex items-center gap-2 pl-2"-->
        <!--                >-->
        <!--                    <point-icon />-->
        <!--                    {{ item }}-->
        <!--                </div>-->
        <!--            </items-list>-->
        <!--        </title-section>-->

        <title-section
            v-if="user.media?.length"
            title="Inspired by"
        >
            <items-list
                v-slot="{ item }"
                :items="user.media"
            >
                <!--                <movie-card :media="item" />-->
            </items-list>
        </title-section>

        <title-section
            v-if="
                user.data?.characters?.length && featureFlags.PROFILE_CHARACTERS
            "
            title="Favorite characters"
        >
            <items-list
                v-slot="{ item }"
                :items="user.data?.characters"
            >
                <p
                    class="whitespace-nowrap rounded-full px-3 py-2 text-xs font-normal text-slate-600"
                    style="background-color: rgba(96, 159, 255, 0.1)"
                >
                    {{ item }}
                </p>
            </items-list>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link @see-all="activeTab = 'stories'">
                    <h4>Your Stories</h4>
                </title-with-link>
            </template>
            <div
                v-if="stories?.length"
                class="flex w-full flex-col gap-4 px-4"
            >
                <story-card
                    v-for="(story, storyID) in stories?.slice(0, 3)"
                    :key="storyID"
                    :card="story"
                />
            </div>
            <router-link
                :to="{ name: 'new-story' }"
                class="w-full px-4"
            >
                <div class="w-full rounded-lg bg-slate-200 p-1">
                    <div
                        class="flex w-full flex-col items-center gap-2 rounded-lg border border-dashed border-gray-400 p-4"
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
            </router-link>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link title="Your Achievements" />
            </template>
            <items-list
                v-slot="{ item }"
                :items="user?.achievements.filter((item) => item.progress)"
                class="gap-8"
            >
                <!--                <achievement-card-->
                <!--                    :item="item"-->
                <!--                    class="h-full"-->
                <!--                />-->
            </items-list>
        </title-section>
    </div>
</template>

<script lang="ts" setup>
import { inject, PropType } from 'vue'
import User from '@/types/user'
import TitleSection from '@/components/TitleSection.vue'
import ItemsList from '@/components/ItemsList.vue'
import StoryCard from '@/components/cards/StoryCard.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'
import featureFlags from '@/types/featureFlags'
import { useQuery } from '@tanstack/vue-query'
import { getStories } from '@/utils/endpoints'
import { tabLayoutActiveTabInjection } from '@/types/injection'

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
})

const activeTab = inject(tabLayoutActiveTabInjection)

const { data: stories } = useQuery({
    queryKey: ['stories'],
    queryFn: () => getStories(),
    select(data) {
        return data.data
    },
})
</script>
