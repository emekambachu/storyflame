<template>
    <div class="w-full flex flex-col gap-2">
        <div class="w-full flex items-start justify-between">
            <h2 class="font-bold text-base text-black">Just Now</h2>
        </div>
        <div class="flex items-start justify-between w-full">
            <div class="flex flex-col items-start">
                <strong class="text-sm text-black font-bold">
                    {{ data.time_in_development }} minutes
                </strong>
                <span class="text-[10px] text-slate-500 font-bold">
                    in development
                </span>
            </div>

            <div class="flex flex-col items-start">
                <p class="text-[10px] pt-1 text-slate-500 font-bold">
                    <strong class="text-black font-bold">
                        {{ data.time_in_development }} Achievements
                    </strong>
                    Earned
                </p>
                <div class="flex items-center">
                    <img
                        v-for="(
                            achievement, achievementID
                        ) in data.achievements"
                        :key="achievementID"
                        :src="achievement.image.path"
                        :class="achievementID !== 0 ? '-ml-2' : 'm-0'"
                        class="rounded-full h-5 object-containt"
                    />
                </div>
            </div>

            <div class="flex flex-col items-end">
                <strong class="text-sm text-black font-bold">
                    {{ data.elements }} elements
                </strong>
                <span class="text-[10px] text-slate-500 font-bold">
                    developed
                </span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
const data = {
    time_in_development: 37,
    elements: 6,
    achievements: [
        { image: { path: 'https://picsum.photos/900' } },
        { image: { path: 'https://picsum.photos/900' } },
        { image: { path: 'https://picsum.photos/900' } },
    ],
}
</script>

<style scoped></style>
