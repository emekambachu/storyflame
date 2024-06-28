<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue'
import axios from 'axios'

import AdminDataPointForm from './AdminDataPointsForm.vue';
import AdminDataPointItem from '@/views/admin/pages/data-points/AdminDataPointsItem.vue'
import baseService from '@/utils/base-service'
import store from '@/store'

const user = computed(() => baseService.getUserFromLocalStorage());

// Slide over
const isSlideoverOpen = ref(false);
const openSlideover = () => {
    isSlideoverOpen.value = true;
};
const closeSlideover = () => {
    isSlideoverOpen.value = false;
};

const emittedDataPoint = (event) => {
    dataPoints.value.unshift(event);
    total.value++;
    // closeSlideover();
}

const loading = ref(false);

const dataPoints = ref([]);
const total = ref(0);

const getDataPoints = async () => {
    loading.value = true;
    await axios.get('/api/admin/data-points', {
        // withCredentials: true,
        headers: {
            "Authorization" : "Bearer " + user.value.token,
            'Accept' : 'application/json',
        },
    }).then((response) => {
        if(response.data.success === true){
            dataPoints.value = response.data.data_points;
            total.value = response.data.total;
        }else{
            console.log(dataPoints.value);
        }
    }).catch((error) => {
        console.log(error);
    });
}

onBeforeMount(() => {
    getDataPoints();
    store.dispatch('getData', {
        url: '/api/categories',
        commit_name: 'SET_CATEGORIES',
    });

    store.dispatch('getData', {
        url: '/api/admin/achievements/min',
        commit_name: 'SET_MIN_ACHIEVEMENTS',
    });

    store.dispatch('getData', {
        url: '/api/admin/summaries/min',
        commit_name: 'SET_MIN_SUMMARIES',
    });
});

</script>

<template>
    <div>
        <div class="flex justify-between items-center mt-4">
            <h2 class="text-2xl font-bold">Data Points ({{ total }})</h2>
            <button @click="openSlideover" class="px-4 py-2 bg-black text-white rounded-full">Create new data point</button>
        </div>

        <AdminDataPointForm
            :isOpen="isSlideoverOpen"
            @close="closeSlideover"
            @formSubmitted="emittedDataPoint"
        >
        </AdminDataPointForm>

        <div class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-stone-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">
                        Name & ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Achievements Linked
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Categories
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Dev Order
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Est. Sec. (To Complete)
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Summaries
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Last Updated
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
                </thead>
                <tbody>
                <admin-data-point-item
                    v-for="datapoint in dataPoints"
                    :key="datapoint.id"
                    :datapoint="datapoint"
                />
                </tbody>
            </table>
        </div>


    </div>
</template>

<style scoped>

</style>
