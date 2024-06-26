<template>
    <page-navigation-layout no-back-text>
        <list-wrapper
            title="Characters"
            :enableFilter="true"
        ></list-wrapper>
    </page-navigation-layout>
</template>

<script setup lang="ts">
import ListWrapper from '@/components/ListWrapper.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
</script>

<style scoped></style>
