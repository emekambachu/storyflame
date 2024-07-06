<script setup lang="ts">
import { defineProps } from 'vue'

const props = defineProps({
    src: {
        type: String,
        default: null,
    },

    alt: {
        type: String,
        default: null,
    },

    style: {
        type: String,
        default: null,
    },
})


</script>

<template>
    <img v-if="src" :class="style" class="w-8 h-8 rounded-full" :src="src" :alt="alt"/>
    <img v-else class="w-8 h-8 rounded-full" src="/images/no-image.png" />
</template>

<style scoped>

</style>
