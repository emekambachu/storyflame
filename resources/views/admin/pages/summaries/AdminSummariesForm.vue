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
                            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-auto">
                                <div class="flex items-center justify-between bg-stone-300 p-3 sticky top-0">

                                    <h2 class="text-md font-bold text-black">
                                        {{ summary !== null ? 'Edit Summary' : 'Create New Summary' }}
                                    </h2>
                                    <button
                                        @click.prevent="summary !== null ? updateSummary() : submitSummary()"
                                        v-if="!loading"
                                        class="px-4 py-2 bg-black text-white rounded-full">
                                        {{ summary !== null ? 'Update' : 'Save' }}
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
                                    {{ summary !== null ? 'Summary Updated Successfully' : 'Summary Created Successfully' }}
                                </div>

                                <div class="space-y-6 bg-white p-6 rounded-lg">

                                    <div class="w-full space-x-4 block sm:flex">
                                        <div class="w-full">
                                            <div class="mb-3">
                                                <label
                                                    class="block text-sm font-medium text-gray-700">
                                                    Summary Name
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
                                            Location
                                        </label>
                                        <textarea
                                            v-model="form.location"
                                            id="purpose"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-black"></textarea>
                                        <p v-if="errors.location" class="text-red-500 text-sm">
                                            {{ errors.location[0] }}
                                        </p>
                                    </div>

                                    <div class="w-full">
                                        <label
                                            for="purpose"
                                            class="block text-sm font-medium text-gray-700">
                                            Purpose
                                        </label>
                                        <textarea
                                            v-model="form.purpose"
                                            id="purpose"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-black"></textarea>
                                        <p v-if="errors.purpose" class="text-red-500 text-sm">
                                            {{ errors.purpose[0] }}
                                        </p>
                                    </div>

                                    <div class="w-full">
                                        <label
                                            for="category"
                                            class="block text-sm font-medium text-gray-700">
                                            Category
                                        </label>
                                        <div v-if="form.categories?.length > 0">
                                            <span
                                                v-for="(category, index) in form.categories"
                                                :key="index"
                                                class="bg-sky-200 text-sky-600 p-1 rounded-md mr-1"
                                            >
                                            <span class="">
                                                {{ category.name }}
                                                <a class="font-extrabold" href="" @click.prevent="deleteCategory">x</a>
                                            </span>
                                        </span>
                                        </div>
                                        <select
                                            @change.prevent="selectCategory"
                                            id="category"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150 text-black">
                                            <option>Select Category</option>
                                            <option class="text-black"
                                                    v-for="category in categories"
                                                    :key="category.id"
                                                    :value="category.id"
                                            >
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <p v-if="errors.categories" class="text-red-500 text-sm">
                                            {{ errors.categories[0] }}
                                        </p>
                                    </div>

                                    <div class="w-full sm:w-1/2">
                                        <label
                                            for="publishAt"
                                            class="block text-sm font-medium text-gray-700">
                                            Publish at
                                        </label>
                                        <input
                                            v-model="form.publish_at"
                                            id="publishAt"
                                            type="date"
                                            :min="new Date().toISOString().split('T')[0]"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150 text-black">
                                    </div>

                                    <hr class="my-4">

                                    <div class="w-full">
                                        <label
                                            for="achievements"
                                            class="block text-sm font-medium text-gray-700">
                                            Link Achievement
                                        </label>
                                        <select
                                            id="category"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150 text-black">
                                            <option>Select Achievement</option>
                                            <option class="text-black"
                                                    v-for="achieve in achievements"
                                                    :key="achieve.id"
                                                    :value="achieve.id"
                                            >
                                                {{ achieve.name }}
                                            </option>
                                        </select>
                                        <p v-if="errors.achievement" class="text-red-500 text-sm">
                                            {{ errors.achievement[0] }}
                                        </p>
                                    </div>

                                    <hr class="my-4">

                                    <div class="w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">
                                            Creation Prompt
                                        </label>
                                        <textarea
                                            v-model="form.creation_prompt"
                                            id="extractionDescription"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-black"></textarea>
                                        <p v-if="errors.creation_prompt" class="text-red-500 text-sm">
                                            {{ errors.creation_prompt[0] }}
                                        </p>
                                    </div>

                                    <div class="w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">
                                            Example Summary
                                        </label>
                                        <textarea
                                            v-model="form.example_summary"
                                            id="extractionDescription"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-black"></textarea>
                                        <p v-if="errors.example_summary" class="text-red-500 text-sm">
                                            {{ errors.example_summary[0] }}
                                        </p>
                                    </div>

                                    <hr class="my-4">

                                    <div>
                                        <label
                                            for="category"
                                            class="block text-sm font-medium text-gray-700">
                                            Linked Data Points
                                        </label>
                                        <select
                                            @change="selectDataPoint"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150 text-black">
                                            <option>Select</option>
                                            <option
                                                class="text-black"
                                                v-for="data in dataPoints"
                                                :key="data.id"
                                                :value="data.id"
                                            >
                                                {{ data.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.data_points?.length > 0" class="mt-1">
                                            <div
                                                v-for="(data, index) in form.data_points"
                                                :key="index"
                                                class="w-full flex justify-center mb-1"
                                            >
                                                <span class="w-11/12 mr-1 p-1 bg-stone-200 text-black rounded-sm">
                                                    {{ data.name }}
                                                </span>
                                                <span @click="deleteDataPoint" class="w-1/12">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                        <g clip-path="url(#clip0_1946_18183)">
                                                        <path d="M3.33337 5.83331H16.6667" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M8.33337 9.16669V14.1667" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M11.6666 9.16669V14.1667" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M4.16663 5.83331L4.99996 15.8333C4.99996 16.2753 5.17555 16.6993 5.48811 17.0118C5.80068 17.3244 6.2246 17.5 6.66663 17.5H13.3333C13.7753 17.5 14.1992 17.3244 14.5118 17.0118C14.8244 16.6993 15 16.2753 15 15.8333L15.8333 5.83331" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M7.5 5.83333V3.33333C7.5 3.11232 7.5878 2.90036 7.74408 2.74408C7.90036 2.5878 8.11232 2.5 8.33333 2.5H11.6667C11.8877 2.5 12.0996 2.5878 12.2559 2.74408C12.4122 2.90036 12.5 3.11232 12.5 3.33333V5.83333" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_1946_18183">
                                                        <rect width="20" height="20" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <p v-if="errors.data_points" class="text-red-500 text-sm">
                                            {{ errors.data_points[0] }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="category"
                                            class="block text-sm font-medium text-gray-700">
                                            Linked Summaries
                                        </label>
                                        <select
                                            @change="selectSummary"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150 text-black">
                                            <option>Select</option>
                                            <option
                                                class="text-black"
                                                v-for="summary in summaries"
                                                :key="summary.id"
                                                :value="summary.id"
                                            >
                                                {{ summary.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.summaries?.length > 0" class="mt-1">
                                            <div
                                                v-for="(summary, index) in form.summaries"
                                                :key="index"
                                                class="w-full flex justify-center mb-1"
                                            >
                                                <span class="w-11/12 mr-1 p-1 bg-stone-200 text-black rounded-sm">
                                                    {{ summary.name }}
                                                </span>
                                                <span @click="deleteSummary" class="w-1/12">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                        <g clip-path="url(#clip0_1946_18183)">
                                                        <path d="M3.33337 5.83331H16.6667" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M8.33337 9.16669V14.1667" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M11.6666 9.16669V14.1667" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M4.16663 5.83331L4.99996 15.8333C4.99996 16.2753 5.17555 16.6993 5.48811 17.0118C5.80068 17.3244 6.2246 17.5 6.66663 17.5H13.3333C13.7753 17.5 14.1992 17.3244 14.5118 17.0118C14.8244 16.6993 15 16.2753 15 15.8333L15.8333 5.83331" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M7.5 5.83333V3.33333C7.5 3.11232 7.5878 2.90036 7.74408 2.74408C7.90036 2.5878 8.11232 2.5 8.33333 2.5H11.6667C11.8877 2.5 12.0996 2.5878 12.2559 2.74408C12.4122 2.90036 12.5 3.11232 12.5 3.33333V5.83333" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_1946_18183">
                                                        <rect width="20" height="20" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <p v-if="errors.summaries" class="text-red-500 text-sm">
                                            {{ errors.summaries[0] }}
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
    summary: {
        type: Object,
        required: false,
        default: null,
    },
});

const emit = defineEmits(['close', 'closed', 'formSubmitted', 'formUpdated']);

const closeModal = () => {
    emit('close');
};

const user = computed(() => baseService.getUserFromLocalStorage());

const errors = ref({});
const loading = ref(false);
const submitted = ref(false);

const categories = computed(() => store.state.categories);
const data_points = computed(() => store.state.data_points);
const summaries = computed(() => store.state.summaries);

const selectCategory = (e) => {
    if(e.target.value !== 'Select Category' && !form.categories.includes(e.target.value)) {
        form.categories.push({
            id: e.target.value,
            name: e.target.options[e.target.selectedIndex].text,
        });
    }

    console.log("CATEGORIES", form.categories);
};
const deleteCategory = (index) => {
    form.categories.splice(index, 1);
};

const selectSummary = (e) => {
    if(e.target.value !== 'Select' && !form.summaries.includes(e.target.value)) {
        form.summaries.push({
            id: e.target.value,
            name: e.target.options[e.target.selectedIndex].text,
        });
    }
};
const deleteSummary = (index) => {
    form.summaries.splice(index, 1);
};

const selectDataPoint = (e) => {
    if(e.target.value !== 'Select' && !form.data_points.includes(e.target.value)) {
        form.data_points.push({
            id: e.target.value,
            name: e.target.options[e.target.selectedIndex].text,
        });
    }
};
const deleteDataPoint = (index) => {
    form.data_points.splice(index, 1);
};


const form = reactive({
    name: props.summary !== null ? props.summary.name : '',
    location: props.summary !== null ? props.summary.location : '',
    purpose: props.summary !== null ? props.summary.purpose : '',
    creation_prompt: props.summary !== null ? props.summary.creation_prompt : '',
    example_summary: props.summary !== null ? props.summary.example_summary : '',
    publish_at: props.summary !== null ? props.summary.publish_at : '',
    categories: props.summary !== null ? props.summary.categories : [],
    summaries: props.summary !== null ? props.summary.summaries : [],
    data_points: props.summary !== null ? props.summary.data_points : [],
});

const submitSummary = async () => {
    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    const formData = new FormData();
    // iterate and add form data
    Object.keys(form).forEach(function(key) {
        console.log(key); // key
        if(form[key] !== null && form[key] !== ''){
            if(key === 'categories'){
                form[key].forEach((category) => {
                    formData.append('categories[]', category.id);
                });
            } else if(key === 'summaries'){
                form[key].forEach((data) => {
                    formData.append('summaries[]', data.id);
                });
            } else if(key === 'data_points'){
                form[key].forEach((data) => {
                    formData.append('data_points[]', data.id);
                });
            } else {
                formData.append(key, form[key]);
            }
        }
    });

    await axios.post('/api/admin/summaries/store', formData, {
        headers: {
            'content-type': 'multipart/form-data',
            'Accept' : 'application/json',
            "Authorization" : "Bearer " + user.value.token,
        }
    }).then((response) => {
        if (response.data.success){
            errors.value = []; // Empty error messages
            submitted.value = true;
            // Emit the submitted data to the parent component
            emit('formSubmitted', response.data.summary);
            // Empty form fields
            Object.keys(form).forEach(function(key) {
                if(key === 'categories'){
                    form[key] = [];
                }else if(key === 'summaries'){
                    form[key] = [];
                }else if(key === 'data_points'){
                    form[key] = [];
                }else{
                    form[key] = '';
                }
            });
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

const updateSummary = async () => {

    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    const formData = new FormData();
    // iterate and add form data
    Object.keys(form).forEach(function(key) {
        console.log(key); // key
        if(form[key] !== null && form[key] !== ''){
            if(key === 'categories'){
                form[key].forEach((category) => {
                    formData.append('categories[]', category.id);
                });
            } else if(key === 'summaries'){
                form[key].forEach((data) => {
                    formData.append('summaries[]', data.id);
                });
            } else if(key === 'data_points'){
                form[key].forEach((data) => {
                    formData.append('data_points[]', data.id);
                });
            } else {
                formData.append(key, form[key]);
            }
        }
    });

    await axios.post('/api/admin/summaries/'+props.summary.item_id+'/update', formData, {
        headers: {
            'content-type': 'multipart/form-data',
            'Accept' : 'application/json',
            "Authorization" : "Bearer " + user.value.token,
        }
    }).then((response) => {
        if (response.data.success){
            errors.value = []; // Empty error messages
            submitted.value = true;
            // Emit the submitted data to the parent component
            emit('formUpdated', response.data.summary);
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
