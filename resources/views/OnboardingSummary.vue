<template>
  <div class="w-full min-h-screen flex flex-col items-center pt-10 pb-24 px-4">
    <template v-if="loading">
      <loading-tab class="w-full min-h-screen" />
    </template>

    <template v-else>
      <button class="text-orange-500 mr-0 ml-auto">Edit</button>

      <div class="flex flex-col gap-8">
        <div class="flex flex-col items-center gap-3">
          <div
            class="w-28 h-28 bg-gray-200 shrink-0 rounded-full flex items-center justify-center relative"
          >
            <user-icon class="text-neutral-400" />

            <verified-icon class="absolute -bottom-2 text-blue-700" />
          </div>

          <div class="flex flex-col items-center text-center gap-2">
            <h2 class="text-2xl text-neutral-700 font-bold">{{ user.name }}</h2>
            <p class="text-sm text-neutral-500 font-normal">
              {{ user.profession }}
            </p>
            <p
              class="text-sm text-neutral-500 font-normal flex items-center gap-1"
            >
              {{ user.genres[0] }}
              <point-icon />
              {{ user.genres[1] }}
            </p>
          </div>
        </div>

        <div
          class="flex items-center gap-2 justify-between max-w-full overscroll-auto"
        >
          <button
            v-for="(tab, tabID) in tabs"
            :key="tabID"
            :class="
              activeTab == tabID
                ? 'bg-neutral-800 text-pure-white'
                : 'bg-gray-200 text-neutral-400'
            "
            class="rounded-full text-sm font-semibold whitespace-nowrap select-none py-1 px-3"
            @click="activeTab = tabID"
          >
            {{ tab }}
          </button>
        </div>

        <profile-tab
          v-if="activeTab == 0"
          :user="user"
        />

        <stories-tab
          v-if="activeTab == 1 || activeTab == 0"
          :user="user"
        />

        <achievements-tab
          v-if="activeTab == 2 || activeTab == 0"
          :achievements="user.achievements"
        />

        <div class="w-full bg-white px-4 pb-4 fixed bottom-0 left-0">
          <button
            class="py-4 w-full rounded-full items-center justify-center bg-red-600 text-white text-base font-semibold"
          >
            Create an account
          </button>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import LoadingTab from '@/components/LoadingTab.vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'

import UserIcon from '@/components/icons/UserIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'
import VerifiedIcon from '@/components/icons/VerifiedIcon.vue'

const loading = ref(false)

const user = {
  name: 'Veronica',
  profession: 'TV writer',
  genres: ['Drama', 'Fantasy'],
  about:
    'Veronica is a rising star in the world of dramatic television writing. She recently landed a coveted position as Writer Coordinator on the acclaimed series "For All Mankind", where she will hорe the opportunity to further hone her skills in crafting compelling character-driven narratives.',
  goals: [
    'To write on House of the Dragon',
    'To write on House of the Dragon',
    'To write on House of the Dragon',
  ],
  inspiration: [
    {
      image: null,
    },
    {
      image: { path: 'https://picsum.photos/960' },
    },
    {
      image: { path: 'https://picsum.photos/990' },
    },
  ],

  favorite_characters: [
    'Daenerys Targaryen',
    'Sherlock Holmes',
    'Thomas Shelby',
    'Dean Winchester',
  ],
  stories: {
    finished: [
      {
        image: { path: 'https://picsum.photos/930' },
        title: 'The Horizon Line',
      },
      {
        image: { path: 'https://picsum.photos/960' },
        title: 'Whispers in the Void',
      },
      {
        image: { path: 'https://picsum.photos/990' },
        title: 'Beneath the Neon Sky',
      },
    ],

    in_progress: [
      {
        image: { path: 'https://picsum.photos/900' },
        title: 'The Horizon Line',
        percent: 10,
      },
      {
        image: { path: 'https://picsum.photos/920' },
        title: 'The Horizon Line',
        percent: 30,
      },
      {
        image: { path: 'https://picsum.photos/940' },
        title: 'The Horizon Line',
        percent: 90,
      },
    ],
  },
  achievements: {
    earned: [
      {
        image: {
          path: 'https://picsum.photos/940',
        },
        title: 'Ice Breaker',
        percent: 100,
        achievement_date: '25 Apr',
      },
    ],
    in_progress: [
      {
        image: {
          path: 'https://picsum.photos/940',
        },
        title: 'First Story',
        percent: 60,
        achievement_date: null,
      },
    ],
  },
}

const activeTab = ref(0)
const tabs = ['My profile', 'My stories', 'My achievements']
</script>
<style scoped></style>
