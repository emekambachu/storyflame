<template>
    <button :class="{
        'bg-black text-white rounded-full px-4 py-2 text-base': variant === 'dark',
    }">
        <slot/>
    </button>
</template>

<script setup lang="ts">
defineProps({
    variant: {
        type: String,
        default: 'dark',
    },
})
</script>

<style scoped>

</style>
