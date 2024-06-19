<script setup lang="ts">
import { ref } from 'vue'

</script>

<template>
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Achievements (36)</h2>
            <button class="px-4 py-2 bg-blue-500 text-white rounded">Create new achievement</button>
        </div>
        <table class="w-full">
            <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Achievement name</th>
                <th class="px-4 py-2 text-left">Category</th>
                <th class="px-4 py-2 text-left">Data Points</th>
                <th class="px-4 py-2 text-left">Summaries</th>
                <th class="px-4 py-2 text-left">Dev Order</th>
                <th class="px-4 py-2 text-left">Total impact</th>
                <th class="px-4 py-2 text-left">Last Updated</th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-b">
                <td class="px-4 py-2">Achievement name<br>ID 00001</td>
                <td class="px-4 py-2">Category 1</td>
                <td class="px-4 py-2">+3</td>
                <td class="px-4 py-2">7</td>
                <td class="px-4 py-2">20</td>
                <td class="px-4 py-2">2</td>
                <td class="px-4 py-2">20<br>Jun 17, 2024<br>by Veronika S</td>
            </tr>
            <!-- Repeat the <tr> for each achievement -->
            </tbody>
        </table>
    </div>
</template>

<style scoped>

</style>
