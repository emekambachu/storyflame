<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col gap-3 text-left items-start w-full">
      <h4 class="text-sm text-neutral-700 font-bold">About</h4>
      <p class="text-neutral-950 text-sm font-normal">{{ user.about }}</p>
    </div>

    <div class="flex flex-col gap-3 text-left items-start w-full">
      <h4 class="text-sm text-neutral-700 font-bold">Writing goals</h4>
      <ul>
        <li
          v-for="(goal, goalID) in user.goals"
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
        <image-component
          v-for="(poster, posterID) in user.inspiration"
          :key="posterID"
          :src="poster.image?.path"
          alt="poster"
          class="rounded-lg object-cover w-24 h-36 shrink-0"
        />
      </div>
    </div>

    <div class="flex flex-col gap-3 text-left items-start w-full">
      <h4 class="text-sm text-neutral-700 font-bold">Favorite characters</h4>
      <div class="flex gap-2 items-center flex-wrap">
        <p
          v-for="(character, characterID) in user.favorite_characters"
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

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'
import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped></style>
