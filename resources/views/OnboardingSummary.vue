<template>
    <div class="w-full min-h-screen flex flex-col items-center pt-10 pb-8 px-4">
        <template v-if="loading">
            <div class="flex flex-col items-center mb-[-129px]">
                <svg
                    fill="none"
                    height="61"
                    viewBox="0 0 61 61"
                    width="61"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle
                        cx="30.5"
                        cy="30.228"
                        fill="url(#paint0_linear_203_749)"
                        r="30.228"
                    />
                    <path
                        clip-rule="evenodd"
                        d="M40.927 22.8836C43.0823 27.1195 44.8269 32.0616 44.1214 36.7816C43.0665 43.8188 37.3354 48.2294 30.8457 48.3942C19.8878 48.6725 14.5825 39.3798 17.5185 29.5357C18.2987 26.7108 20.1565 24.0371 22.1623 21.8445C23.0905 20.8416 23.6106 20.0599 23.7227 22.2899C23.7519 23.3627 24.1422 24.39 24.4714 25.0191C24.7264 25.5152 24.773 25.4062 25.1375 25.0191C27.9121 21.9928 29.2528 17.6639 30.1139 13.7451C30.5492 11.7638 30.5219 11.712 32.1197 12.6301C35.5156 14.5814 39.2087 19.5144 40.927 22.8836Z"
                        fill="white"
                        fill-rule="evenodd"
                    />
                    <defs>
                        <linearGradient
                            id="paint0_linear_203_749"
                            gradientUnits="userSpaceOnUse"
                            x1="0.271973"
                            x2="30.5"
                            y1="0"
                            y2="60.4561"
                        >
                            <stop stop-color="#FF9202" />
                            <stop
                                offset="1"
                                stop-color="#FD2D24"
                            />
                        </linearGradient>
                    </defs>
                </svg>

                <h1 class="text-black text-2xl font-bold">
                    Story
                    <span class="text-orange-500 -ml-1">Flame</span>
                </h1>
            </div>

            <div class="mx-auto my-auto flex flex-col items-center gap-2">
                <AppLoader />
                <span class="text-black text-sm font-normal text-center">
          Processing your data..
        </span>
            </div>
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

                    <div class="flex flex-col items-center text-center gap-2">
                        <h2 class="text-2xl text-neutral-700 font-bold">{{ user.name }}</h2>
                        <p class="text-sm text-neutral-500 font-normal">
                            {{ user.level }}
                        </p>
                        <p
                            class="text-sm text-neutral-500 font-normal flex items-center gap-1"
                        >
                            {{ user.genre_focus.join(' • ')}}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 text-left items-start w-full">
                    <h5 class="text-sm text-neutral-700 font-bold">About</h5>
                    <p class="text-neutral-950 text-sm font-normal">{{ user.bio }}</p>
                </div>

                <div class="flex flex-col gap-3 text-left items-start w-full">
                    <h5 class="text-sm text-neutral-700 font-bold">Writing goals</h5>
                    <ul>
                        {{user.motivation}}
<!--                        <li-->
<!--                            v-for="(goal, goalID) in user."-->
<!--                            :key="goalID"-->
<!--                            class="text-neutral-950 text-sm font-normal flex items-center gap-2 pl-2"-->
<!--                        >-->
<!--                            <dot-icon />-->
<!--                            {{ goal }}-->
<!--                        </li>-->
                    </ul>
                </div>

                <div class="flex flex-col gap-3 text-left items-start w-full">
                    <h5 class="text-sm text-neutral-700 font-bold">Inspired by</h5>
                    <div class="flex item-start gap-3">
                        <image-component
                            v-for="(poster, posterID) in user.media"
                            :key="posterID"
                            :src="poster.image?.path"
                            alt="poster"
                            class="rounded-lg object-cover w-24 h-36 shrink-0"
                        />
                    </div>
                </div>

                <div class="flex flex-col gap-3 text-left items-start w-full">
                    <h5 class="text-sm text-neutral-700 font-bold">
                        Favorite characters
                    </h5>
                    <div class="flex gap-2 items-center flex-wrap">
                        <p
                            v-for="(character, characterID) in user.characters"
                            :key="characterID"
                            class="text-slate-600 text-xs font-normal px-3 py-2 rounded-full"
                            style="background-color: rgba(96, 159, 255, 0.1)"
                        >
                            {{ character }}
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import AppLoader from '@/components/AppLoader.vue'
import DotIcon from '@/components/icons/DotIcon.vue'
import ImageComponent from '@/components/ImageComponent.vue'

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

const user = ref(JSON.parse(localStorage.getItem('onboarding.user') || 'null') || {})
</script>
<style scoped></style>
