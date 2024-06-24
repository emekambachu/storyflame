<template>
    <page-navigation-layout fixed no-back-text transparent>
        <lists-hero-section :data="data" />

        <list-wrapper
            :enableFilter="true"
            :search-value="searchValue"
            placeholder="Enter keyword"
            title="My Stories"
            @update:search-value="searchValue = $event"
        >
            <template #modal>
                <div class="flex flex-col px-1">
                    <h6 class="text-base font-semibold text-stone-800">
                        Filter by
                    </h6>
                    <span class="mt-4 text-sm font-semibold text-stone-500">
                        Readiness
                    </span>
                    <div class="mt-3 flex gap-2">
                        <div
                            :class="
                                selectedOptions.includes(0)
                                    ? 'bg-stone-800 text-stone-50'
                                    : 'bg-stone-100 text-stone-500'
                            "
                            class="flex w-full max-w-14 flex-col items-center gap-2 rounded-lg p-2 text-sm font-bold"
                            @click="toggleOption(0)"
                        >
                            All
                            <span class="text-xs font-normal">
                                {{ data.stories?.length }}
                            </span>
                        </div>
                        <div
                            v-for="(option, optionID) in filter"
                            :key="optionID"
                            :class="
                                selectedOptions.includes(option)
                                    ? 'bg-stone-800 text-stone-50'
                                    : 'bg-stone-100 text-stone-500'
                            "
                            class="flex w-full max-w-14 flex-col items-center gap-2 rounded-lg p-2 text-xs"
                            @click="toggleOption(option)"
                        >
                            <flame-icon
                                :priority="option"
                                class="!w-6 !bg-white"
                            />
                            {{ counts[optionID] }}
                        </div>
                    </div>
                </div>
            </template>
            <template #closeButton1>
                <button
                    class="text-medium mt-6 w-full rounded-full bg-black py-4 text-center text-base text-stone-50 hover:opacity-80"
                    @click="applyFilters"
                >
                    Show {{ totalFilteredStories }} results
                </button>
            </template>
            <template #closeButton2>
                <button
                    class="text-medium mx-auto -mt-3 text-center text-base text-black"
                    @click="resetFilters"
                >
                    Reset
                </button>
            </template>

            <template #content>
                <div class="mt-4 flex flex-col gap-4">
                    <div v-if="selectedOptions.includes(0)">
                        <div
                            v-for="(stories, index) in filteredStories"
                            :key="index"
                            class="flex flex-col gap-4"
                        >
                            <h6
                                class="mt-4 text-sm font-semibold text-gray-500"
                            >
                                {{ titles[filter[index]] }}:
                                {{ stories.length }}
                            </h6>
                            <story-card
                                v-for="story in stories"
                                :key="story.title"
                                :card="story"
                            />
                        </div>
                    </div>
                    <div v-else>
                        <div
                            v-for="(stories, index) in filteredStories"
                            v-show="selectedOptions.includes(filter[index])"
                            :key="index"
                            class="flex flex-col gap-4"
                        >
                            <h6
                                class="mt-4 text-sm font-semibold text-gray-500"
                            >
                                {{ titles[filter[index]] }}:
                                {{ stories.length }}
                            </h6>
                            <story-card
                                v-for="story in stories"
                                :key="story.title"
                                :card="story"
                            />
                        </div>
                    </div>
                </div>
            </template>
        </list-wrapper>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import ListWrapper from '@/components/ListWrapper.vue'
import ListsHeroSection from '@/components/ListsHeroSection.vue'

import StoryCard from '@/components/cards/StoryCard.vue'

import FlameIcon from '@/components/FlameInProgressCircle.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

const searchValue = ref('')
const selectedOptions = ref([0])
const titles = {
    4: 'Started',
    3: 'In Progress',
    2: 'Near Completion',
    1: 'Complete',
    0: 'All Stories',
}

const data = {
    image: {
        path: 'https://picsum.photos/900',
    },
    title: 'Ep 208: The Red Wedding',
    story: 'Game of Thrones',
    episode: 'Season 2',
    percent: 60,

    stories: [
        {
            readiness: 1,
            image: {
                path: null,
            },
            title: 'Hallo of Two Cities',
            type: 'Novel',

            genres: ['Historical', 'Drama', 'Adventure'],
            description:
                'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness...', // Description or a brief summary of the story
        },
        {
            readiness: 2,
            image: {
                path: 'https://picsum.photos/900',
            },
            title: 'Hallo of Two Cities',
            type: 'Novel',

            genres: ['Historical', 'Drama', 'Adventure'],
            description:
                'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness...', // Description or a brief summary of the story
        },
        {
            readiness: 3,
            image: {
                path: 'https://picsum.photos/900',
            },
            title: 'First of Two Cities',
            type: 'Novel',

            genres: ['Historical', 'Drama', 'Adventure'],
            description:
                'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness...', // Description or a brief summary of the story
        },
        {
            readiness: 3,
            image: {
                path: 'https://picsum.photos/900',
            },
            title: 'Second of Two Cities',
            type: 'Novel',

            genres: ['Historical', 'Drama', 'Adventure'],
            description:
                'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness...', // Description or a brief summary of the story
        },
        {
            readiness: 4,
            image: {
                path: 'https://picsum.photos/900',
            },
            title: 'Test Tale of Two Cities',
            type: 'Novel',
            genres: ['Historical', 'Drama', 'Adventure'],
            description:
                'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness...', // Description or a brief summary of the story
        },
    ],
}
const filter = [4, 3, 2, 1]

const filteredStories = computed(() => {
    return filter.map((readiness) => {
        return data.stories.filter((story) => {
            return (
                story.readiness === readiness &&
                story.title
                    .toLowerCase()
                    .includes(searchValue.value.toLowerCase())
            )
        })
    })
})

const counts = computed(() =>
    filteredStories.value.map((group) => group.length)
)

function toggleOption(option) {
    const index = selectedOptions.value.indexOf(option)
    if (option === 0 && index === -1) {
        selectedOptions.value = [0] // If 'All' is clicked when not active, reset to only 'All'
    } else if (option === 0 && index !== -1) {
        selectedOptions.value = [] // If 'All' is clicked when active, deselect it
    } else {
        const allIndex = selectedOptions.value.indexOf(0)
        if (allIndex !== -1) {
            selectedOptions.value.splice(allIndex, 1) // Remove 'All' if another option is selected
        }
        if (index !== -1) {
            selectedOptions.value.splice(index, 1) // Toggle off if currently selected
        } else {
            selectedOptions.value.push(option) // Add to selections
        }
    }
}

const totalStories = computed(() => data.stories.length)

const totalFilteredStories = computed(() => {
    if (selectedOptions.value.includes(0)) {
        return totalStories.value
    } else {
        return filteredStories.value
            .filter((_, index) => selectedOptions.value.includes(filter[index]))
            .reduce((acc, curr) => acc + curr.length, 0)
    }
})

function resetFilters() {
    selectedOptions.value = [0]
}
</script>

<style scoped></style>
