<script setup lang="ts">

import { reactive } from 'vue'

const form = reactive({
    bio: '',
});

</script>

<template>
<div>
    <div class="flex flex-col gap-6 w-full mb-auto mt-0">

        <div>
            <label
                class="text-black text-base font-bold w-full"
                for="bio"
            >
                Bio
            </label>
            <textarea
                id="bio"
                v-model="form.bio"
                class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                placeholder="Enter your bio"
                required>
            </textarea>
        </div>
    </div>

    <div class="flex flex-col items-center w-full gap-2 mt-auto mb-0">
        <button
            class="w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700"
            type="submit"
            @click.prevent="updateBio"
        >
            Update Bio
        </button>
    </div>
</div>
</template>

<style scoped>

</style>
