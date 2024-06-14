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
            <h2 class="text-2xl sticky font-bold text-neutral-700">
                {{ user.name }}
            </h2>
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
