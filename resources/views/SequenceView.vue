<template>
    <div class="pb-6 flex flex-col gap-6">
        <div
            class="pt-6 flex flex-col gap-8 w-full"
            :class="data?.image?.path ? 'pb-4' : ''"
            :style="`background: ${
                data?.image?.path
                    ? `linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url(${data.image.path}) lightgray 50% / cover no-repeat;`
                    : 'transparent'
            }`"
        >
            <button
                class="flex items-center gap-2 px-4"
                :class="data?.image?.path ? 'text-white' : 'text-black'"
            >
                <chevron-icon />
                <span class="text-lg font-normal">Back</span>
            </button>

            <div class="flex flex-col gap-1.5 px-4">
                <span
                    class="text-sm font-normal opacity-70"
                    :class="data?.image?.path ? 'text-white' : 'text-black'"
                >
                    {{ data.genre }}
                </span>

                <div class="flex items-center w-full justify-between">
                    <h1
                        class="font-bold text-3xl"
                        :class="data?.image?.path ? 'text-white' : 'text-black'"
                    >
                        {{ data.name }}
                    </h1>

                    <div class="w-10 h-10 shrink-0">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="44"
                            height="43"
                            viewBox="0 0 44 43"
                            fill="none"
                        >
                            <path
                                d="M7.06851 17.5289C7.58699 15.5938 8.48153 13.7799 9.70105 12.1906C10.9206 10.6013 12.4412 9.26776 14.1761 8.26612L16.129 11.6488C14.8383 12.3939 13.7071 13.386 12.7998 14.5684C11.8926 15.7507 11.2271 17.1002 10.8413 18.5398L7.06851 17.5289Z"
                                fill="#16BFD6"
                            />
                            <path
                                d="M13.0867 33.996C10.028 31.8663 7.85476 28.6904 6.97739 25.068C6.10002 21.4456 6.57918 17.6273 8.32442 14.334C10.0696 11.0408 12.9602 8.50035 16.4503 7.19245C19.9404 5.88454 23.7886 5.89963 27.2684 7.23487L25.8512 10.9282C23.2738 9.93926 20.4235 9.92808 17.8385 10.8968C15.2535 11.8656 13.1125 13.7472 11.8199 16.1864C10.5272 18.6257 10.1723 21.4538 10.8222 24.1368C11.472 26.8198 13.0816 29.1721 15.3472 30.7495L13.0867 33.996Z"
                                fill="#EAEAEA"
                            />
                            <path
                                d="M33.4652 31.3616C32.0245 33.0621 30.2286 34.4262 28.2039 35.358C26.1792 36.2898 23.975 36.7667 21.7462 36.755C19.5175 36.7433 17.3183 36.2433 15.3036 35.2903C13.2888 34.3373 11.5073 32.9544 10.0845 31.2389C8.66168 29.5233 7.63206 27.5168 7.06808 25.3606C6.5041 23.2043 6.41946 20.9507 6.82012 18.7582C7.22078 16.5657 8.09701 14.4877 9.38715 12.6702C10.6773 10.8528 12.35 9.34017 14.2876 8.23874L16.211 11.6222C14.7677 12.4426 13.5218 13.5694 12.5608 14.9231C11.5998 16.2769 10.9471 17.8247 10.6487 19.4578C10.3502 21.091 10.4133 22.7696 10.8334 24.3758C11.2535 25.9819 12.0204 27.4764 13.0802 28.7543C14.14 30.0321 15.467 31.0622 16.9677 31.7721C18.4685 32.482 20.1065 32.8544 21.7667 32.8631C23.4268 32.8718 25.0687 32.5166 26.5768 31.8225C28.0849 31.1285 29.4226 30.1124 30.4958 28.8457L33.4652 31.3616Z"
                                fill="#EAEAEA"
                            />
                            <path
                                d="M36.5901 17.4641C37.1903 19.6105 37.313 21.8624 36.9493 24.0614C36.5857 26.2603 35.7447 28.3528 34.4854 30.1918C33.226 32.0307 31.5791 33.5713 29.6603 34.7053C27.7416 35.8392 25.5976 36.539 23.3794 36.7553C21.1611 36.9715 18.9224 36.6991 16.8207 35.9571C14.719 35.2152 12.8055 34.0217 11.2147 32.4606C9.62388 30.8996 8.39447 29.0089 7.61294 26.9216C6.83142 24.8344 6.51676 22.6012 6.69111 20.3792L10.5711 20.6837C10.4413 22.3388 10.6756 24.0022 11.2578 25.5569C11.8399 27.1117 12.7557 28.52 13.9406 29.6828C15.1255 30.8455 16.5509 31.7345 18.1163 32.2872C19.6818 32.8399 21.3494 33.0428 23.0017 32.8817C24.654 32.7206 26.251 32.1994 27.6802 31.3547C29.1094 30.5101 30.3362 29.3625 31.2742 27.9927C32.2122 26.623 32.8387 25.0643 33.1095 23.4264C33.3804 21.7885 33.289 20.1111 32.8419 18.5123L36.5901 17.4641Z"
                                fill="#FE9A24"
                            />
                            <path
                                d="M21.9886 6.22406C24.2172 6.25113 26.4129 6.76622 28.421 7.7331C30.4292 8.69998 32.201 10.0951 33.612 11.8205C35.023 13.5458 36.0387 15.5593 36.5878 17.7194C37.1369 19.8795 37.206 22.1337 36.7902 24.3233C36.3745 26.513 35.4839 28.585 34.1813 30.3934C32.8786 32.2019 31.1955 33.703 29.2503 34.791C27.3052 35.8791 25.1452 36.5277 22.9224 36.6912C20.6996 36.8547 18.468 36.5291 16.3846 35.7374L17.7613 32.1146C19.3154 32.7052 20.98 32.948 22.6381 32.826C24.2962 32.7041 25.9074 32.2203 27.3584 31.4086C28.8093 30.597 30.0648 29.4773 31.0365 28.1283C32.0082 26.7793 32.6725 25.2337 32.9827 23.6004C33.2928 21.967 33.2413 20.2855 32.8317 18.6742C32.4221 17.0629 31.6644 15.5609 30.6119 14.2739C29.5594 12.987 28.2377 11.9463 26.7397 11.225C25.2418 10.5038 23.604 10.1196 21.9415 10.0994L21.9886 6.22406Z"
                                fill="#FE9A24"
                            />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        v-for="(genre, genreID) in data.genres"
                        :key="genreID"
                        :class="data?.image?.path ? 'text-white' : 'text-black'"
                        class="text-sm font-normal opacity-40 gap-1.5 flex items-center"
                    >
                        <span v-if="genreID !== 0"><point-icon /></span>
                        {{ genre }}
                    </span>
                </div>

                <p
                    class="text-sm opacity-70"
                    :class="data?.image?.path ? 'text-white' : 'text-black'"
                >
                    {{ data.description }}
                </p>
            </div>
        </div>

        <div class="px-4 py-2">
            <discuss-component />
        </div>
        <div class="flex items-center relative">
            <div
                v-for="(tab, tabID) in tabs"
                :key="tabID"
                :class="
                    tabID == selectedTab
                        ? 'text-red-600 border-red-600'
                        : 'text-neutral-500 border-neutral-300'
                "
                class="mx-4 text-base font-normal border-b-2 relative z-10"
                @click="selectedTab = tabID"
            >
                {{ tab }}
            </div>
            <div class="w-full h-[2px] bg-neutral-300 absolute bottom-0 z-5" />
        </div>
        <sequence-details-tab :data="data" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import SequenceDetailsTab from '@/components/SequenceDetailsTab.vue'
import DiscussComponent from '@/components/DiscussComponent.vue'

const selectedTab = ref(0)
const tabs = ['Details', 'Progress', 'Elements', 'Script']

const data = {
    name: 'Arrival of King Robert and the Royal Family',
    story: {
        name: 'Game of Thrones',
    },
    image: {
        path: null,
    },
    kickstart:
        'Kickstart the main political plot by bringing the royal family to Winterfell, highlighting the close yet strained relationship between the Starks and the Baratheons.',
    drives_scene:
        'King Robert drives the sequence by his actions and decisions, from the moment of his arrival to his insistence on visiting the crypts.',
    goals: [
        {
            part: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            progress: {
                status: 'In progress',
                done: 2,
                total_amount: 4,
            },
        },
        {
            part: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            progress: {
                status: 'Finished',
                done: 2,
                total_amount: 4,
            },
        },
    ],
    conflict_sources: [
        'Political Obligations vs. Personal Desires: Ned Stark faces the tension between his duty to the king and his desire to stay in the North away from the politics of the capital.',
        'Past Relationships vs. Present Realities: The reminiscence of past friendships contrasts with current political necessities and personal changes.',
        'Expectations vs. Reality: The grandeur and behavior of the royal family may not meet the expectations of the Stark family, highlighting differences and brewing discontent.',
    ],
}
</script>

<style scoped></style>
