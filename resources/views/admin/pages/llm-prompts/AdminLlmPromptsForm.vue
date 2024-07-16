<!-- Slideover.vue -->
<template>
    <transition name="slide" @after-leave="$emit('closed')">
        <div v-if="isOpen" class="fixed inset-0 overflow-hidden z-50">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <div class="relative w-screen max-w-md overflow-y-auto">

                        <!--Close slide-over-->
                        <div class="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4">
                            <button @click="closeModal" class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                <span class="sr-only">Close panel</span>
                                <!-- Heroicon name: outline/x -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form>
                            <!--Contents-->
                            <div class="h-screen flex flex-col bg-white shadow-xl overflow-y-auto">
                                <div class="flex items-center justify-between bg-stone-300 p-3 sticky top-0">

                                    <h2 class="text-md font-bold text-black">
                                        {{ prompt !== null ? 'Update Prompt' : 'Create New Prompt' }}
                                    </h2>
                                    <button
                                        @click.prevent="prompt !== null ? updatePrompt() : submitPrompt()"
                                        v-if="!loading"
                                        class="px-4 py-2 bg-black text-white rounded-full">
                                        {{ prompt !== null ? 'Update' : 'Save' }}
                                    </button>
                                    <button v-else type="button" disabled class="px-4 py-2 bg-black text-white rounded-full">
                                        <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-gray-100 animate-spin dark:text-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="white"/>
                                        </svg>
                                        Loading...
                                    </button>

                                </div>
                                <div v-if="submitted" class="flex items-center justify-center bg-emerald-400 p-2 text-stone-600">
                                    {{ prompt !== null ? 'Prompt Updated Successfully' : 'Prompt Created Successfully' }}
                                </div>

                                <div class="space-y-6 bg-white p-6 rounded-lg">

                                    <div class="w-full space-x-4 block sm:flex">
                                        <div class="w-full">
                                            <div class="mb-3">
                                                <label
                                                    class="block text-sm font-medium text-gray-700">
                                                    Version Purpose
                                                </label>
                                                <input
                                                    v-model="form.name"
                                                    id="achievementName"
                                                    type="text"
                                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150 text-black">
                                                <p v-if="errors.name" class="text-red-500 text-sm">
                                                    {{ errors.name[0] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <label
                                            for="purpose"
                                            class="block text-sm font-medium text-gray-700">
                                            Prompt Value
                                        </label>
                                        <div class="relative">
                                            <textarea
                                                v-model="form.prompt_value"
                                                id="purpose"
                                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-black font-mono whitespace-pre"
                                                placeholder="Enter code format"
                                                rows="10"
                                            ></textarea>
                                        </div>
                                        <p v-if="errors.prompt_value" class="text-red-500 text-sm">
                                            {{ errors.prompt_value[0] }}
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { defineProps, defineEmits, reactive, ref, computed } from 'vue'
import store from '@/store/index.js'
import axios from 'axios'
import baseService from '@/utils/base-service.js'

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
    prompt: {
        type: Object,
        required: false,
        default: null,
    },
});

const emit = defineEmits(['close', 'closed', 'formSubmitted', 'formUpdated']);

const closeModal = () => {
    emit('close');
};

const token = computed(() => baseService.getTokenFromLocalStorage());

const errors = ref({});
const loading = ref(false);
const submitted = ref(false);

const form = reactive({
    name: props.prompt !== null ? props.prompt.name : '',
    prompt_value: props.prompt !== null ? props.prompt.current_prompt_value : '',
});

const submitPrompt = async () => {
    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    await axios.post('/api/admin/llm-prompts/store', form, {
        headers: {
            'Accept' : 'application/json',
            "Authorization" : "Bearer " + token.value,
        }
    }).then((response) => {
        if (response.data.success){
            errors.value = []; // Empty error messages
            submitted.value = true;
            // Empty form fields
            Object.keys(form).forEach(function(key) {
                form[key] = '';
            });
            // Emit the submitted data to the parent component
            emit('formSubmitted', response.data.prompt);
        }

    }).catch((error) => {

        if([401, 402, 422].includes(error.response.status)){
            console.log(error.response);

            if(Object.keys(error.response?.data?.errors).length > 0){
                errors.value = error.response?.data?.errors;
            }

            if(error.response?.data?.server_error){
                errors.value.server_error = 'Server error. Please try again later or contact your admin.';
            }
        }

        console.log(error);
    });
    loading.value = false;
}

const updatePrompt = async () => {

    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    await axios.post('/api/admin/llm-prompts/'+props.prompt.slug+'/update', form, {
        headers: {
            'Accept' : 'application/json',
            "Authorization" : "Bearer " + token.value,
        }
    }).then((response) => {
        if (response.data.success){
            errors.value = []; // Empty error messages
            submitted.value = true;
            // Emit the submitted data to the parent component
            emit('formUpdated', response.data.prompt);
        }

    }).catch((error) => {

        if(error.response && [401, 402, 422].includes(error.response.status)){
            console.log(error.response);

            if(Object.keys(error.response?.data?.errors).length > 0){
                errors.value = error.response?.data?.errors;
            }

            if(error.response?.data?.server_error){
                errors.value.server_error = 'Server error. Please try again later or contact your admin.';
            }
        }

        console.log(error);
    });
    loading.value = false;
}

</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

</style>
