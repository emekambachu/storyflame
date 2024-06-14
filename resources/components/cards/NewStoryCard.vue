<template>
    <router-link :to="{
        name: 'new-story',
    }" class="rounded-lg bg-stone-100 p-1">
        <div
            class="h-full py-3 px-2 flex flex-col items-center justify-center rounded-lg border border-dashed border-stone-400"
        >
            <book-with-plus />
            <p class="text-stone-400 text-sm font-normal">New Story</p>
        </div>
    </router-link>
</template>

<script setup lang="ts">
import BookWithPlus from '@/components/icons/BookWithPlus.vue'
</script>

<style scoped></style>
