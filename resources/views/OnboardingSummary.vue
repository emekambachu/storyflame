<template>
	<div class="w-full min-h-screen flex flex-col items-center pt-10 pb-8 px-4">
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
						<div class="flex flex-col items-center">
							<div
								class="w-24 h-24 bg-gray-200 shrink-0 rounded-full flex items-center justify-center"
							>
								<user-icon class="text-neutral-400" />
							</div>

							<div class="flex flex-col gap-3 text-left items-start w-full">
								<h5 class="text-sm text-neutral-700 font-bold">About</h5>
								<p class="text-neutral-950 text-sm font-normal">
									{{ user.bio }}
								</p>
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
								v-if="activeTab == 1"
								:user="user"
							/>

							<achievements-tab
								v-if="activeTab == 2"
								:achievements="user.achievements"
							/>
						</div>
					</div>
				</div>
			</div>
		</template>
	</div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import LoadingTab from '@/components/LoadingTab.vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import UserIcon from '@/components/icons/UserIcon.vue'

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
//
//   favorite_characters: [
//     'Daenerys Targaryen',
//     'Sherlock Holmes',
//     'Thomas Shelby',
//     'Dean Winchester',
//   ],
// }

const activeTab = ref(0)
const tabs = ['My profile', 'My stories', 'My achievements']
</script>
<style scoped></style>
