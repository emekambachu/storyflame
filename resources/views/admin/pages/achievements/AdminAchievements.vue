<script setup lang="ts">
import { ref } from 'vue'

</script>

<template>
    <div>
        <div class="flex justify-between items-center mt-4">
            <h2 class="text-2xl font-bold">Achievements (36)</h2>
            <button class="px-4 py-2 bg-black text-white rounded-full">Create new achievement</button>
        </div>

        <div class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-stone-200">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Img
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name & ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Data Points
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Summaries
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Dev Order
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Impact
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Last Updated
                    </th>

                </tr>
                </thead>
                <tbody>
                <tr class="bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Apple MacBook Pro 17"
                    </th>
                    <td class="px-6 py-4">
                        Silver
                    </td>
                    <td class="px-6 py-4">
                        Laptop
                    </td>
                    <td class="px-6 py-4">
                        $2999
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Microsoft Surface Pro
                    </th>
                    <td class="px-6 py-4">
                        White
                    </td>
                    <td class="px-6 py-4">
                        Laptop PC
                    </td>
                    <td class="px-6 py-4">
                        $1999
                    </td>
                </tr>
                <tr class="bg-white dark:bg-gray-800">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Magic Mouse 2
                    </th>
                    <td class="px-6 py-4">
                        Black
                    </td>
                    <td class="px-6 py-4">
                        Accessories
                    </td>
                    <td class="px-6 py-4">
                        $99
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


    </div>
</template>

<style scoped>

</style>
