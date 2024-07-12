<template>
    <img
        v-if="src"
        :src="src"
        alt="image"
        class="cursor-pointer border-stone-100 bg-cover bg-center bg-no-repeat hover:border hover:shadow-lg"
        @click="emit('on-click')"
    />
    <div
        v-else
        class="flex cursor-pointer items-center justify-center bg-stone-300 hover:bg-stone-200"
        @click="emit('on-click')"
    >
        <no-image class="w-6 text-stone-400" />
    </div>
</template>

<script setup lang="ts">
import NoImage from '@/components/icons/NoImage.vue'
const emit = defineEmits(['on-click'])
const props = defineProps({
    src: {
        type: String,
        default: null,
    },
})
</script>

<style scoped></style>
