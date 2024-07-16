<script setup lang="ts">
import {defineProps} from 'vue'

const props = defineProps({
    classes: {
        type: String,
        default: '',
    },
    loading: {
        type: Boolean,
        default: false,
    },
})

</script>

<template>
    <button v-if="!loading"
            class="w-full flex justify-center py-1 px-4 rounded-full text-sm font-semibold"
            :class="classes"
            type="submit"
    >
        <slot></slot>
    </button>

    <button
        v-else
        disabled
        class="w-full flex justify-center py-1 px-4 rounded-full text-sm font-semibold"
        :class="classes"
    >
        <svg
            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            ></circle>
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V2.5"
            ></path>
        </svg>
    </button>
</template>

<style scoped>

</style>
