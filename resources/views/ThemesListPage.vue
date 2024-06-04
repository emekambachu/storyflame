<template>
    <lists-hero-section :data="data" />
    <list-wrapper
        title="Sequences"
        :enableFilter="true"
        placeholder="Enter keyword"
        :search-value="searchValue"
        @update:search-value="searchValue = $event"
    >
        <template #modal>
            <div class="px-1 flex flex-col">
                <h6 class="text-stone-800 text-base font-semibold">
                    Filter by
                </h6>
                <span class="mt-4 text-stone-500 text-sm font-semibold">
                    Status
                </span>

                <div class="flex flex-col gap-3 mt-3">
                    <list-option
                        v-for="(option, optionID) in filter"
                        :key="optionID"
                        :status="selectedOptions.includes(option)"
                        textClass="text-base"
                        selectedClass=""
                        class="w-full !px-4 !py-3"
                        multiple
                        @click="
                            selectedOptions.includes(option)
                                ? selectedOptions.splice(
                                      selectedOptions.indexOf(option),
                                      1
                                  )
                                : selectedOptions.push(option)
                        "
                    >
                        <template #text>
                            {{ option }} ({{ counts[optionID] }})
                        </template>
                    </list-option>
                </div>
            </div>
        </template>
        <template #closeButton1>
            <button
                class="bg-black rounded-full py-4 w-full text-stone-50 text-base text-medium text-center mt-6 hover:opacity-80"
            >
                Show {{ totalFilteredThemes }} results
            </button>
        </template>
        <template #closeButton2>
            <button
                class="text-center text-black text-base text-medium -mt-3 mx-auto"
                @click="resetFilters"
            >
                Reset
            </button>
        </template>

        <template #content>
            <div class="mt-4 flex flex-col gap-4">
                <div
                    v-for="(themes, index) in filteredThemes"
                    :key="index"
                    class="flex flex-col gap-4"
                    v-show="selectedOptions.includes(filter[index])"
                >
                    <h6 class="text-sm text-gray-500 font-semibold mt-4">
                        {{ titles[index] }}: {{ themes.length }}
                    </h6>
                    <theme-card
                        v-for="theme in themes"
                        :key="theme.title"
                        :card="theme"
                    />
                </div>
            </div>
        </template>
    </list-wrapper>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import ListOption from '@/components/ListOption.vue'
import ListWrapper from '@/components/ListWrapper.vue'

import ListsHeroSection from '@/components/ListsHeroSection.vue'
import ThemeCard from '@/components/cards/ThemeCard.vue'

const searchValue = ref('')
const selectedOptions = ref(['Earned', 'In progress', 'Not Started'])
const titles = { 0: 'Earned', 1: 'In progress', 2: 'Not Started' }

const data = {
    image: {
        path: 'https://picsum.photos/900',
    },
    title: 'Ep 208: The Red Wedding',
    story: 'Game of Thrones',
    episode: 'Season 2',
    percent: 60,

    themes: [
        {
            progress: 0,
            type: 'major',
            title: 'First',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
        {
            progress: 0,
            type: 'major',
            title: 'Second',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
        {
            progress: 20,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
        {
            progress: 80,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
        {
            progress: 100,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
    ],
}

const filter = ['Earned', 'In progress', 'Not Started']

const filteredThemes = computed(() => {
    return filter.map((status) => {
        if (!selectedOptions.value.includes(status)) {
            return [] // If the status is not selected, return an empty array
        }
        return data.themes.filter((theme) => {
            const matchesStatus =
                (status === 'Earned' && theme.progress === 100) ||
                (status === 'In progress' &&
                    theme.progress > 0 &&
                    theme.progress < 100) ||
                (status === 'Not Started' && theme.progress === 0)
            return (
                matchesStatus &&
                theme.title
                    .toLowerCase()
                    .includes(searchValue.value.toLowerCase())
            )
        })
    })
})

const totalFilteredThemes = computed(() => {
    return filteredThemes.value.reduce(
        (total, current) => total + current.length,
        0
    )
})

const counts = computed(() => filteredThemes.value.map((group) => group.length))

function resetFilters() {
    selectedOptions.value = ['Earned', 'In progress', 'Not Started']
}
</script>

<style scoped></style>
