<template>
    <div class="flex flex-col lg:flex-row w-full items-center justify-between">
        <div
            class="flex w-full flex-col items-center justify-center bg-stone-50 py-4 lg:py-0 gap-1 lg:gap-4 lg:bg-stone-light-gradient lg:max-w-1/2 lg:min-h-dvh"

        >
            <a
                href="/"
                class="flex items-center gap-2"
            >
                <img
                    class="w-32 lg:w-56"
                    src="@/assets/StoryFlame-brandmark_textmark-color.png"
                />
            </a>
            <h1 class="hidden lg:flex font-main text-sm lg:text-2xl font-medium text-stone-600 lg:text-stone-950 select-none">
                Speak your story into existence.
            </h1>
        </div>
        <div
            class="max-w-1/2 flex h-full min-h-dvh w-full flex-col items-center justify-center bg-stone-50 px-2 py-6"
        >
            <slot />
        </div>
    </div>
</template>
<script setup>
import LogoIcon from '@/components/icons/LogoIcon.vue'
import {onMounted, ref} from "vue";
</script>
