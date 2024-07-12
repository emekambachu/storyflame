<template>
    <router-link
        :to="{
            name: 'new-story',
        }"
        class="rounded-lg bg-stone-100 p-1 hover:bg-stone-200"
    >
        <div
            class="flex h-full flex-col items-center justify-center rounded-lg border border-dashed border-stone-400 px-2 py-3"
        >
            <book-with-plus />
            <p class="text-sm font-normal text-stone-400">New Story</p>
        </div>
    </router-link>
</template>

<script setup lang="ts">
import BookWithPlus from '@/components/icons/BookWithPlus.vue'
</script>

<style scoped></style>
