<template>
    <div
        ref="container"
        class="sticky flex flex-col items-end gap-3 pt-10"
    >
        <div class="h-28 w-full">
            <div
                ref="userIcon"
                class="absolute flex aspect-square h-28 shrink-0 origin-bottom items-center justify-center rounded-full bg-gray-200"
            >
                <img
                    v-if="user.avatar.path"
                    :src="user.avatar.path"
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
            class="mb-1 flex w-full flex-col items-start gap-2"
        >
            <h2 class="sticky text-2xl font-bold text-neutral-700">
                {{ user.name }}
            </h2>
            <p
                v-if="user.profession"
                class="sticky text-sm font-normal text-neutral-500"
            >
                {{ user.profession }}
            </p>
            <p
                v-if="
                    !user.data?.genre_focus?.length &&
                    !user.data?.writing_medium?.length
                "
                class="sticky text-sm font-normal text-neutral-500"
            ></p>
            <p
                v-if="user.data?.genre_focus?.length"
                class="sticky flex items-center gap-1 text-sm font-normal text-neutral-500"
            >
                {{ user.data?.genre_focus.join(' & ') }}
            </p>
            <p
                v-if="user.data?.genre_focus?.length"
                class="sticky flex items-center gap-1 text-sm font-normal text-neutral-500"
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
                    scale: 0.4,
                    left: ['50%', '0%'],
                    translateX: ['-50%', '0%'],
                },
            ],
            [
                userIcon.value,
                {
                    translateY: ['0%', '45%'],
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
