<template>
    <div
        ref="container"
        class="flex flex-col items-end gap-3 pt-6"
    >
        <div class="h-28 w-full">
            <div
                ref="userIcon"
                class="absolute flex aspect-square h-28 shrink-0 origin-top items-center justify-center rounded-full bg-gray-200"
            >
                <img
                    v-if="user.avatar"
                    :src="user.avatar"
                    alt="avatar"
                    class="rounded-full w-full h-full object-cover object-center"
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
            <div class="flex justify-center mx-auto">
                <h2 class="text-2xl sticky font-bold text-neutral-700 mr-2">
                    {{ getNames() }}
                </h2>
                <span class="my-auto text-sm">
                    <router-link
                        :to="{name: 'ProfileEdit'}">
                       <EditIconSquare color="#fc3737" />
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
import EditIconSquare from '@/components/icons/EditIconSquare.vue'
import { defineProps } from 'vue'

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
})

const userIcon = ref<HTMLDivElement | null>(null)
const verifiedIcon = ref<InstanceType<typeof UserIcon> | null>(null)
const container = ref<HTMLDivElement | null>(null)
const userInfo = ref<HTMLDivElement | null>(null)

const getNames = () => {
    let name = "No Name";
    if(props.name){
        name = props.name;
    }else if(props.full_name && props.full_name !== ""){
        name = props.full_name;
    }else{
        name = "No Name";
    }
    return name;
}

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
            translateY: ['0%', '-30px'],
        }),
        {
            offset: ['start start', '100px'],
        }
    )
})
</script>

<style scoped></style>
