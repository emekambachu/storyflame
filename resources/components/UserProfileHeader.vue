<template>
    <div
        ref="container"
        class="flex flex-col items-end pt-10 gap-3 sticky"
    >
        <div class="h-28 w-full">
            <div
                ref="userIcon"
                class="bg-gray-200 absolute h-28 origin-bottom aspect-square shrink-0 rounded-full flex items-center justify-center"
            >
                <user-icon class="text-neutral-400 w-2/3" />

                <verified-icon
                    ref="verifiedIcon"
                    class="absolute -bottom-2 text-blue-700"
                />
            </div>
        </div>

        <div
            ref="userInfo"
            class="flex flex-col items-start gap-2 mb-1 w-full"
        >
            <h2 class="text-2xl text-neutral-700 font-bold sticky">
                {{ user.name }}
            </h2>
            <p
                v-if="user.profession"
                class="text-sm text-neutral-500 font-normal sticky"
            >
                {{ user.profession }}
            </p>
            <p
                v-if="
                    !user.data?.genre_focus.length &&
                    !user.data?.writing_medium.length
                "
                class="text-sm text-neutral-500 font-normal sticky"
            ></p>
            <p
                v-if="user.data?.genre_focus.length"
                class="text-sm text-neutral-500 font-normal flex items-center gap-1 sticky"
            >
                {{ user.data?.genre_focus.join(' & ') }}
            </p>
            <p
                v-if="user.data?.genre_focus.length"
                class="text-sm text-neutral-500 font-normal flex items-center gap-1 sticky"
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
import { animate, scroll, timeline } from 'motion'

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
    console.log(userIcon.value)
    scroll(
        // animate(userIcon.value, {
        //     scale: 0.5,
        //     left: ['50%', '0%'],
        //     translateX: ['-50%', '0%'],
        //     translateY: ['0%', '55%'],
        // }),
        timeline([
            [
                userIcon.value,
                {
                    scale: 0.5,
                    left: ['50%', '0%'],
                    translateX: ['-50%', '0%'],
                },
            ],
            [
                userIcon.value,
                {
                    translateY: ['0%', '60%'],
                },
            ],
        ]),
        {
            offset: ['start start', '180px'],
        }
    )
    // animate(userInfo.value?.children, {
    //     left: ['50%', '0%'],
    //     translateX: ['-50%', '0%'],
    // }),
    scroll(
        animate(userInfo.value?.children, {
            left: ['50%', '100px'],
            translateX: ['-50%', '0%'],
        }),
        {
            offset: ['start start', '150px'],
        }
    )
    // scroll(
    //     animate(userInfo.value?.lastElementChild, {
    //         opacity: 0,
    //     }),
    //     {
    //         offset: ['start start', '150px'],
    //     }
    // )
    // scroll(
    //     animate(userInfo.value, {
    //         width: ['100%', '50%'],
    //     }),
    //     {
    //         offset: ['start start', '50px'],
    //     }
    // )

    // scroll(
    //     animate(verifiedIcon.value.$el, {
    //         opacity: 0,
    //     })
    // )
    // scroll(
    //     animate(container.value, {
    //         translateY: ['0%', '-100px'],
    //     }),
    //     {
    //         offset: ['start start', '100px'],
    //     }
    // )
})
</script>

<style scoped></style>
