<template>
    <div class="flex flex-col items-center pt-10 gap-3 sticky">
        <div
            class="bg-gray-200 h-28 aspect-square shrink-0 rounded-full flex items-center justify-center relative"
        >
            <user-icon class="text-neutral-400" />

            <verified-icon class="absolute -bottom-2 text-blue-700" />
        </div>

        <div class="flex flex-col items-center text-center gap-2 mb-1">
            <h2 class="text-2xl text-neutral-700 font-bold">
                {{ user.name }}
            </h2>
            <p
                v-if="user.profession"
                class="text-sm text-neutral-500 font-normal"
            >
                {{ user.profession }}
            </p>
            <p
                v-if="user.data?.genre_focus.length"
                class="text-sm text-neutral-500 font-normal flex items-center gap-1"
            >
                {{ user.data?.genre_focus.join(' & ') }}
            </p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import VerifiedIcon from '@/components/icons/VerifiedIcon.vue'
import UserIcon from '@/components/icons/UserIcon.vue'
import { PropType } from 'vue'
import User from '@/types/user'

defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
})
</script>

<style scoped></style>
