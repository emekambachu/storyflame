<template>
    <div class="flex flex-col gap-8 py-2 px-4">
        <title-section
            title-class="text-lg text-black font-bold"
            title="Goals and Motivation:"
        >
            <ul class="flex flex-col gap-1">
                <li
                    v-for="(goal, goalID) in character.goals"
                    :key="goalID"
                    class="font-normal text-black text-sm flex items-center gap-2"
                >
                    <point-icon />
                    {{ goal }}
                </li>
            </ul>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Common clichés"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div
                v-for="(cliche, clicheID) in character.cliches"
                :key="clicheID"
                class="flex flex-col"
            >
                <h6 class="text-sm text-black font-bold">
                    {{ cliche.title }}
                </h6>

                <p class="text-sm text-black font-normal opacity-50">
                    {{ cliche.description }}
                </p>
            </div>
        </title-section>

        <title-section
            title-class="text-lg text-black font-bold"
            title="Backstory"
        >
            <p class="font-normal text-black text-sm flex items-center gap-2">
                {{ character.backstory }}
            </p>
        </title-section>

        <title-section
            title-class="text-lg text-black font-bold"
            title="Strengths"
        >
            <div class="flex flex-wrap gap-2">
                <p
                    v-for="(strength, strengthID) in character.strengths"
                    :key="strengthID"
                    class="font-normal text-green-muted text-xs flex items-center gap-2 bg-green-50 py-2 px-3 rounded-full"
                >
                    {{ strength }}
                </p>
            </div>
        </title-section>

        <title-section
            title-class="text-lg text-black font-bold"
            title="Personality Traits"
        >
            <ul class="flex flex-wrap gap-y-2 gap-x-4">
                <li
                    v-for="(goal, goalID) in character.goals"
                    :key="goalID"
                    class="font-normal text-black text-sm flex items-start gap-2 max-w-[160px]"
                >
                    <point-icon class="mt-2 shrink-0" />
                    {{ goal }}
                </li>
            </ul>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Achievements"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex flex-col gap-6 w-full">
                <title-section
                    title="In progress"
                    title-class="text-lg text-slate-500 font-bold p-0"
                >
                    <items-list
                        v-slot="{ item }"
                        :items="
                            character.achievements.filter(
                                (item) =>
                                    item.progress < 100 && item.progress > 0
                            )
                        "
                    >
                        <achievement-card
                            :item="item"
                            class="h-full"
                        />
                    </items-list>
                </title-section>

                <title-section
                    title="To earn"
                    title-class="text-lg text-slate-500 font-bold p-0"
                >
                    <items-list
                        v-slot="{ item }"
                        :items="
                            character.achievements.filter(
                                (item) => !item.progress
                            )
                        "
                    >
                        <achievement-card
                            :item="item"
                            class="h-full"
                        />
                    </items-list>
                </title-section>

                <title-section
                    title="Earned"
                    title-class="text-lg text-slate-500 font-bold p-0"
                >
                    <items-list
                        v-slot="{ item }"
                        :items="
                            character.achievements.filter(
                                (item) => item.progress == 100
                            )
                        "
                        class="gap-8"
                    >
                        <achievement-card
                            :item="item"
                            class="h-full"
                        />
                    </items-list>
                </title-section>
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'

import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import ItemsList from '@/components/ItemsList.vue'
import AchievementCard from '@/components/cards/AchievementCard.vue'

const props = defineProps({
    character: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
