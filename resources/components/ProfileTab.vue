<template>
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-3 text-left items-start w-full">
            <h4 class="text-sm text-neutral-700 font-bold">About</h4>
            <p class="text-neutral-950 text-sm font-normal">
                {{ user.data?.bio }}
            </p>
        </div>

        <div class="flex flex-col gap-3 text-left items-start w-full">
            <h4 class="text-sm text-neutral-700 font-bold">Writing goals</h4>
            <ul>
                <li
                    v-for="(goal, goalID) in user.data?.writing_medium"
                    :key="goalID"
                    class="text-neutral-950 text-sm font-normal flex items-center gap-2 pl-2"
                >
                    <point-icon />
                    {{ goal }}
                </li>
            </ul>
        </div>

        <div class="flex flex-col gap-3 text-left items-start w-full">
            <h4 class="text-sm text-neutral-700 font-bold">Inspired by</h4>
            <div class="flex item-start gap-3">
                <div
                    v-for="media in user.data?.media"
                    :key="media.id"
                    class="w-24 flex flex-col gap-1"
                >
                    <image-component
                        :src="media.poster"
                        alt="poster"
                        class="rounded-lg object-cover w-24 h-36 shrink-0"
                    />
                    <h4 class="text-xs line-clamp-2">
                        {{ media.title }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 text-left items-start w-full">
            <h4 class="text-sm text-neutral-700 font-bold">
                Favorite characters
            </h4>
            <div class="flex gap-2 items-center flex-wrap">
                <p
                    v-for="(character, characterID) in user.data?.characters"
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

<script lang="ts" setup>
import PointIcon from '@/components/icons/PointIcon.vue'
import ImageComponent from '@/components/ImageComponent.vue'
import { PropType } from 'vue'
import User from '@/types/user'

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
})
</script>

<style scoped></style>
