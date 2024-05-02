<template>
  <div class="flex flex-col items-center">
    <div class="flex flex-col items-center mb-[-129px]">
      <logo-icon />

      <h1 class="text-black text-2xl font-bold">
        Story
        <span class="text-orange-500 -ml-1">Flame</span>
      </h1>
    </div>

    <div class="mx-auto my-auto flex flex-col items-center gap-2">
      <AppLoader />
      <span class="text-black text-sm font-normal text-center">
        Processing your data..
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import LogoIcon from '@/components/icons/LogoIcon.vue'

import AppLoader from '@/components/AppLoader.vue'
</script>

<style scoped></style>
