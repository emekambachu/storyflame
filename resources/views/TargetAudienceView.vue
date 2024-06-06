<template>
    <div class="w-full flex flex-col pb-8">
        <div
            class="py-8 flex flex-col items-center px-4"
            :style="`background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), #404040;            `"
        >
          <button class="ml-0 mr-auto"><chevron-icon class="w-5 h-5 text-stone-500" /></button>
          
            <h1 class="text-xl text-white font-bold">{{ data.title }}</h1>
            <span class="text-sm text-white opacity-50 mt-2">
               Target Audience
            </span>

           
        </div>

        <div class="w-full flex flex-col gap-2 bg-slate-100">
            <div class="bg-white p-4 flex flex-col gap-3">
                <p class="text-stone-700 text-sm">
                    {{ data.description }}
                </p>

                <title-section class="!gap-2">
                    <template #title>
                        <h4 class="text-sm text-slate-600 font-bold">
                            Favorite fandoms:
                        </h4>
                        <div class="flex items-center gap-3 w-full max-w-full overflow-auto">
                        <image-component
                            v-for="(fandom, fandomID) in data.favorite_fandoms"
                            :key="fandomID"
                            :src="fandom.image.path"
                            class="rounded-lg w-[108px] h-40"
                        />
                    </div>
                    </template>
                </title-section>
            </div>

            <div class="bg-white p-4 flex flex-col gap-3">
            

                <title-section class="!gap-2">
                    <template #title>
                        <h4 class="text-sm text-slate-600 font-bold">
                            Viewing habits:
                        </h4>
                        <div class="flex items-center gap-3 w-full max-w-full overflow-auto">
                            <text-list :items="data.habits" />
                    </div>
                    </template>
                </title-section>

                <title-section class="!gap-2">
                    <template #title>
                        <h4 class="text-sm text-slate-600 font-bold">
                            Preferences
                        </h4>
                        <div class="flex flex-col items-start gap-3 w-full">
                        <preference-card
                            v-for="(preference, preferenceID) in data.preferences"
                            :key="preferenceID"
                            :card="preference"
                        />
                    </div>
                    </template>
                </title-section>

                <title-section class="!gap-2">
                    <template #title>
                        <h4 class="text-sm text-slate-600 font-bold">
                            Interests:
                        </h4>

                        <div class="w-full flex items-start gap-2">
                            <div class="w-full flex flex-col items-start gap-3 w-full max-w-full overflow-auto">
                                <interest-card
                                v-for="(interest, interestID) in data.interest"
                                :key="interestID"
                                :card="interest"
                            />
                            </div>
                            <div class="w-full flex flex-col items-start gap-3 w-full max-w-full overflow-auto">
                                <interest-card
                                v-for="(interest, interestID) in data.not_interest"
                                :key="interestID"
                                :card="interest"
                            />
                            </div>
               

                    </div>
                    </template>
                </title-section>
            </div>

       

        

        </div>

    </div>
</template>

<script setup lang="ts">
import TextList from '@/components/TextList.vue'
import TitleSection from '@/components/TitleSection.vue'
import ImageComponent from '@/components/ImageComponent.vue'

import ChevronIcon from '@/components/icons/ChevronIcon.vue'
import InterestCard from '@/components/cards/InterestCard.vue'
import PreferenceCard from '@/components/cards/PreferenceCard.vue'


const data = {
    title: 'Fantasy Enthusiast',
    description:
        'Craves complex world-building, political intrigue, and morally ambiguous characters, seeks stories with high stakes and unpredictable plot twists.',
    image: { path: 'https://picsum.photos/900' },
    favorite_fandoms: [
    {
        image: { path: 'https://picsum.photos/900' }
    },
    {
        image: { path: 'https://picsum.photos/900' }
    },
    {
        image: { path: 'https://picsum.photos/900' }
    }],
    habits: [
        {
            title: '',
            description:
                'Fantasy-themed TV shows and movies',
        },
        {
            title: '',
            description:
                'Role-playing games like Dungeons & Dragons',
        },
    ],

    preferences: [
        {
            prefer: true,
            description: 'Prefers narratives that subvert genre conventions and challenge traditional hero archetypes.'
        },
        {
            prefer: false,
            description: 'Dislikes overly simplistic or clichéd fantasy tropes, prefers narratives that subvert genre conventions and challenge traditional hero archetypes.'
        }
    ],
    interest: [
        {
            prefer: true,
            description: 'Fantasy worlds'
        },
        {
            prefer: true,
            description: 'Simplistic plots'
        }
    ],
    not_interest: [
        {
            prefer: false,
            description: 'Fantasy worlds'
        },
        {
            prefer: false,
            description: 'Simplistic plots'
        }
    ]
   
}


</script>

<style scoped></style>
