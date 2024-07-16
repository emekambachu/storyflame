<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue'
import axios from 'axios'

import AdminLlmPromptsForm from '@/views/admin/pages/llm-prompts/AdminLlmPromptsForm.vue'
import AdminLlmPromptsItem from '@/views/admin/pages/llm-prompts/AdminLlmPromptsItem.vue'
import baseService from '@/utils/base-service'
import store from '@/store'

const token = computed(() => baseService.getTokenFromLocalStorage());

// Slide over
const isSlideoverOpen = ref(false);
const openSlideover = () => {
    isSlideoverOpen.value = true;
};
const closeSlideover = () => {
    isSlideoverOpen.value = false;
};

const emittedPrompt = (event) => {
    prompts.value.unshift(event);
    total.value++;
    // closeSlideover();
}

const loading = ref(false);

const prompts = ref([]);
const total = ref(0);

const getPrompts = async () => {
    loading.value = true;
    await axios.get('/api/admin/llm-prompts', {
        // withCredentials: true,
        headers: {
            "Authorization" : "Bearer " + token.value,
            'Accept' : 'application/json',
        },
    }).then((response) => {
        if(response.data.success === true){
            prompts.value = response.data.prompts;
            total.value = response.data.total;
        }else{
            console.log(prompts.value);
        }
    }).catch((error) => {
        console.log(error);
    });
}

onBeforeMount(() => {
    getPrompts();
});

</script>

<template>
    <div>
        <div class="flex justify-between items-center mt-4">
            <h2 class="text-2xl font-bold">LLM Prompts ({{ total ?? 0 }})</h2>
            <button @click="openSlideover" class="px-4 py-2 bg-black text-white rounded-full">Create New Prompt</button>
        </div>

        <AdminLlmPromptsForm
            :isOpen="isSlideoverOpen"
            @close="closeSlideover"
            @formSubmitted="emittedPrompt"
        >
        </AdminLlmPromptsForm>

        <div class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-stone-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Current Version
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Versions
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Last Updated
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
                </thead>
                <tbody>
                <admin-llm-prompts-item
                    v-for="prompt in prompts"
                    :key="prompt.id"
                    :prompt="prompt"
                />
                </tbody>
            </table>
        </div>


    </div>
</template>

<style scoped>

</style>
