<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue'
import axios from 'axios'

import AdminAchievementsForm from './AdminAchievementsForm.vue';
import AdminAchievementsItem from '@/views/admin/pages/achievements/AdminAchievementsItem.vue'
import baseService from '@/utils/base-service'

const user = ref(baseService.getUserFromLocalStorage());

// Slide over
const isSlideoverOpen = ref(false);
const openSlideover = () => {
    isSlideoverOpen.value = true;
};
const closeSlideover = () => {
    isSlideoverOpen.value = false;
};

const loading = ref(false);

const achievements = ref([]);
const total = ref(0);

const getAchievements = async () => {
    loading.value = true;
    await axios.get('/api/admin/achievements', {
        // withCredentials: true,
        headers: {
            "Authorization" : "Bearer " + user.value.token,
            'Accept' : 'application/json',
        },
    }).then((response) => {
        if(response.data.success === true){
            achievements.value = response.data.achievements;
            total.value = response.data.total;
        }else{
            console.log(achievements.value);
        }
        console.log(achievements.value);
    }).catch((error) => {
        console.log(error);
    });
}

onBeforeMount(() => {
    getAchievements();
});

</script>

<template>
    <div>
        <div class="flex justify-between items-center mt-4">
            <h2 class="text-2xl font-bold">Achievements ({{ total }})</h2>
            <button @click="openSlideover" class="px-4 py-2 bg-black text-white rounded-full">Create new achievement</button>
        </div>

        <AdminAchievementsForm :isOpen="isSlideoverOpen" @close="closeSlideover">
<!--            <template v-slot:title>-->
<!--                Slideover Title-->
<!--            </template>-->
<!--            <template v-slot:content>-->
<!--                <p>Slideover content goes here.</p>-->
<!--            </template>-->
        </AdminAchievementsForm>

        <div class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-stone-100">
                    <tr>
                    <th scope="col" class="px-6 py-3 text-center">
                        Img
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Name & ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Data Points
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Summaries
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Dev Order
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Total Impact
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Last Updated
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
                </thead>
                <tbody>
                    <admin-achievements-item
                        v-for="achievement in achievements"
                        :key="achievement.id"
                        :achievement="achievement"
                    />
                </tbody>
            </table>
        </div>


    </div>
</template>

<style scoped>

</style>
