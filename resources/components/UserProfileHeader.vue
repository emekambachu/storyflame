<template>
    <div
        ref="container"
        class="flex flex-col items-end gap-3 pt-10"
    >
        <div class="h-28 w-full">
            <div
                ref="userIcon"
                class="absolute flex aspect-square h-28 shrink-0 origin-top items-center justify-center rounded-full bg-gray-200"
            >
                <img
                    v-if="user.avatar?.path"
                    :src="user.avatar?.path"
                    alt="avatar"
                    class="rounded-full"
                />
                <user-icon
                    v-else
                    class="w-2/3 text-neutral-400"
                />

                <verified-icon
                    ref="verifiedIcon"
                    class="absolute -bottom-2 text-blue-700"
                />
            </div>
        </div>

        <div
            ref="userInfo"
            class="mb-1 sticky bottom-0 flex w-full flex-col items-start gap-2"
        >
            <div class="flex">
                <h2 class="text-2xl sticky font-bold text-neutral-700 mr-2">
                    {{ user.name }}
                </h2>
                <span class="my-auto text-sm">
                    <router-link
                        :to="{name: 'ProfileEdit'}">
                       <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" fill="#e71d1d" stroke="#e71d1d">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                            <g id="SVGRepo_iconCarrier"><defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#e71d1d"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#e71d1d]"> </path> </g> </g> </g> </g>
                        </svg>
                    </router-link>
                </span>
            </div>
            <p
                v-if="user.profession"
                class="text-sm font-normal text-neutral-500"
            >
                {{ user.profession }}
            </p>
            <p
                v-if="
                    !user.data?.genre_focus?.length &&
                    !user.data?.writing_medium?.length
                "
                class="text-sm font-normal text-neutral-500"
            ></p>
            <p
                v-if="user.data?.genre_focus?.length"
                class="flex items-center gap-1 text-sm font-normal text-neutral-500"
            >
                {{ user.data?.genre_focus.join(' & ') }}
            </p>
            <p
                v-if="user.data?.genre_focus?.length"
                class="flex items-center gap-1 text-sm font-normal text-neutral-500"
            >
                {{ user.data?.writing_medium.join(' & ') }}
            </p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import VerifiedIcon from '@/components/icons/VerifiedIcon.vue'
import UserIcon from '@/components/icons/UserIcon.vue'
import { onMounted, PropType, ref } from 'vue'
import User from '@/types/user'
import { animate, scroll } from 'motion'

defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
})

const userIcon = ref<HTMLDivElement | null>(null)
const verifiedIcon = ref<InstanceType<typeof UserIcon> | null>(null)
const container = ref<HTMLDivElement | null>(null)
const userInfo = ref<HTMLDivElement | null>(null)

onMounted(() => {
    scroll(
        animate(userIcon.value, {
            scale: 0.4,
            left: ['50%', '0%'],
            translateX: ['-50%', '0%'],
        }),
        {
            offset: ['start start', '100px'],
        }
    )
    scroll(
        animate(userInfo.value?.children, {
            left: ['50%', '100px'],
            translateX: ['-50%', '0%'],
            translateY: ['0%', '-10px'],
        }),
        {
            offset: ['start start', '100px'],
        }
    )
})
</script>

<style scoped></style>
