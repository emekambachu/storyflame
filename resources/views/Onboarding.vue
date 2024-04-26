<template>
    <div class="flex flex-col items-center text-center">
        <aside class="flex flex-col w-full">
            <h2>
                Getting to know you
            </h2>
            <div class="bg-orange-600 w-full h-2">

            </div>
        </aside>
        <main v-if="steps[step]" class="grow flex flex-col items-center">
            <h3>
                {{ steps[step].subtitle }}
            </h3>
            <h1>
                {{ steps[step].title }}
            </h1>
            <voice-button @transcribed="(text)=>{
                data[steps[step].property] = text
                step++
            }" />
        </main>
        <pre class="text-start">{{ data }}</pre>
    </div>
</template>

<script lang="ts" setup>

import { computed, ref } from 'vue'
import VoiceButton from '../components/VoiceButton.vue'


const step = ref(0)
const data = ref({
    name: '',
    goal: '',
    genre: '',
})

const steps = computed(() => [
    {
        subtitle: 'Hello, welcome to StoryFlame!',
        title: 'What is your name?',
        property: 'name',
    },
    {
        subtitle: `Nice to meet you, ${data.value.name}!`,
        title: 'What is your goal?',
        property: 'goal',
    },
    {
        subtitle: 'Great!',
        title: 'What genre do you like?',
        property: 'genre',
    },
])
</script>
