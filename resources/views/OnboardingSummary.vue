<template>
  <div class="w-full min-h-screen flex flex-col items-center pt-10 pb-24 px-4">
    <template v-if="loading">
      <loading-tab class="w-full min-h-screen" />
    </template>

        <template v-else>
            <button class="text-orange-500 mr-0 ml-auto">Edit</button>
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 bg-gray-200 shrink-0 rounded-full flex items-center justify-center"
                    >
                        <svg
                            fill="none"
                            height="60"
                            viewBox="0 0 61 60"
                            width="61"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M30.5 6C27.3174 6 24.2652 7.26428 22.0147 9.51472C19.7643 11.7652 18.5 14.8174 18.5 18C18.5 21.1826 19.7643 24.2348 22.0147 26.4853C24.2652 28.7357 27.3174 30 30.5 30C33.6826 30 36.7348 28.7357 38.9853 26.4853C41.2357 24.2348 42.5 21.1826 42.5 18C42.5 14.8174 41.2357 11.7652 38.9853 9.51472C36.7348 7.26428 33.6826 6 30.5 6ZM15.527 33C14.7368 32.9964 13.9537 33.149 13.2226 33.4489C12.4915 33.7489 11.8269 34.1903 11.2669 34.7478C10.7069 35.3053 10.2625 35.9679 9.95929 36.6976C9.65608 37.4274 9.49999 38.2098 9.5 39C9.5 44.073 11.999 47.898 15.905 50.391C19.751 52.842 24.935 54 30.5 54C36.065 54 41.249 52.842 45.095 50.391C49.001 47.901 51.5 44.07 51.5 39C51.5 37.4087 50.8679 35.8826 49.7426 34.7574C48.6174 33.6321 47.0913 33 45.5 33H15.527Z"
                                fill="#9E9E9E"
                            />
                        </svg>
                    </div>

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
							</div></div>

							<div
								class="flex items-center max-w-full overscroll-auto">

								<button
									v-for="(tab, tabID) in tabs"
									:key="tabID"
									:class="[
										activeTab == tabID
											? 'text-red-600 border-red-600'
											: 'text-neutral-500 border-neutral-300',
              tabID == 0 ? 'pr-5' : tabID == tabs.length - 1 ? 'pl-5' : 'px-5',
									]"
									class="text-sm font-semibold whitespace-nowrap select-none py-1 border-b"
									@click="activeTab = tabID"
								>
									{{ tab }}
								</button>
							</div>

							<profile-tab
								v-if="activeTab == 0"
								:user="user"
							/>
        <template v-if="activeTab == 0">
          <div class="flex flex-col gap-3 text-left items-start w-full">
            <div class="flex items-center justify-between w-full">
              <h4 class="text-sm text-neutral-700 font-bold">Your stories</h4>

              <button
                class="text-sm text-black font-normal opacity-50"
                @click="activeTab = 1"
              >
                See all
              </button>
            </div>
            <div
              v-if="user.stories?.length"
              class="flex flex-col gap-4 w-full"
            >
              <StoryCard
                v-for="(story, storyID) in user.stories"
                :key="storyID"
                :story="story"
              />
            </div>
            <div
              v-else
              class="w-full p-1 bg-zinc-200 rounded-lg"
            >
              <div
                class="flex flex-col items-center gap-2 rounded-lg w-full p-4 border border-dashed border-gray-400"
              >
                <div
                  class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center bg-gray-400"
                >
                  <plus-icon class="text-white" />
                </div>
                <span class="text-sm text-gray-400 font-noraml">
                  Start building your first story
                </span>
              </div>
            </div>
          </div>

          <div class="flex flex-col gap-3 text-left items-start w-full">
            <div class="flex items-center justify-between w-full">
              <h4 class="text-sm text-neutral-700 font-bold">
                Earned achievements
              </h4>

              <button
                class="text-sm text-black font-normal opacity-50"
                @click="activeTab = 2"
              >
                See all
              </button>
            </div>
            <div class="flex gap-8 items-start max-w-full overflow-auto">
              <AchievementCard
                v-for="(achievement, achievementID) in user.achievements.earned"
                :key="achievementID"
                :item="achievement"
              />
            </div>
          </div>
        </template>

        <stories-tab
          v-if="activeTab == 1"
          :user="user"
        />

							<achievements-tab
								v-if="activeTab == 2"
								:achievements="user.achievements"
							/>
						<div class="w-full bg-white px-4 pb-4 fixed bottom-0 left-0">
          <button
            class="py-4 w-full rounded-full items-center justify-center bg-red-600 text-white text-base font-semibold"
          >
            Create an account
          </button>
        </div></div>
					</div>
				</div>
			</div>
		</template>
	</div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import StoryCard from '@/components/StoryCard.vue'
import AchievementCard from '@/components/AchievementCard.vue'

import LoadingTab from '@/components/LoadingTab.vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'

import UserIcon from '@/components/icons/UserIcon.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'
import VerifiedIcon from '@/components/icons/VerifiedIcon.vue'

const loading = ref(false)

// const user = {
//   name: 'Veronica',
//   profession: 'TV writer',
//   genres: ['Drama', 'Fantasy'],
//   about:
//     'Veronica is a rising star in the world of dramatic television writing. She recently landed a coveted position as Writer Coordinator on the acclaimed series "For All Mankind", where she will hорe the opportunity to further hone her skills in crafting compelling character-driven narratives.',
//   goals: [
//     'To write on House of the Dragon',
//     'To write on House of the Dragon',
//     'To write on House of the Dragon',
//   ],
//   inspiration: [
//     {
//       image: null,
//     },
//     {
//       image: { path: 'https://picsum.photos/960' },
//     },
//     {
//       image: { path: 'https://picsum.photos/990' },
//     },
//   ],

//   favorite_characters: [
//     'Daenerys Targaryen',
//     'Sherlock Holmes',
//     'Thomas Shelby',
//     'Dean Winchester',
//   ],
// stories: [
//     {
//       image: { path: 'https://picsum.photos/900' },
//       title: 'The Horizon Line',
//       type: 'TV series',
//       genres: ['Comedy', 'Fantasy', 'Thriller'],
//       description:
//         'In a world where summers span decades and winters can last a lifetime, the noble houses of Westeros battle for control of the Seven Kingdoms, while an ancient enemy awakens in the North, threatening the realm with destruction.',
//     },
//     {
//       image: { path: 'https://picsum.photos/920' },
//       title: 'The Horizon Line',
//       type: 'TV series',
//       genres: ['Comedy', 'Fantasy', 'Thriller'],
//       description:
//         'In a world where summers span decades and winters can last a lifetime, the noble houses of Westeros battle for control of the Seven Kingdoms, while an ancient enemy awakens in the North, threatening the realm with destruction.',
//     },
//     {
//       image: { path: 'https://picsum.photos/940' },
//       title: 'The Horizon Line',
//       type: 'TV series',
//       genres: ['Comedy', 'Fantasy', 'Thriller'],
//       description:
//         'In a world where summers span decades and winters can last a lifetime, the noble houses of Westeros battle for control of the Seven Kingdoms, while an ancient enemy awakens in the North, threatening the realm with destruction.',
//     },
//   ],
//   achievements: {
//     earned: [
//       {
//         image: {
//           path: 'https://picsum.photos/940',
//         },
//         title: 'Ice Breaker',
//         percent: 100,
//         achievement_date: '25 Apr',
//       },
//     ],
//     in_progress: [
//       {
//         image: {
//           path: 'https://picsum.photos/940',
//         },
//         title: 'First Story',
//         percent: 60,
//         achievement_date: null,
//       },
//     ],
//   },
// }


const activeTab = ref(0)
const tabs = ['My profile', 'My stories', 'My achievements']
</script>
<style scoped></style>
