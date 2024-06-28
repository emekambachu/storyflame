<template>
    <page-navigation-layout fixed transparent>
        <div class="h-full mx-auto w-full grow flex-col">

            <div class="w-full h-full grow flex-col bg-gradient-dark">

            </div>

        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

const loading = ref(true)
// const user = ref<User | null>(null)
const { user, getUser } = useAuthStore()

onMounted(() => {

})
</script>
<style scoped>
.bg-gradient-dark{
    background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%);
    background-color: #404040;
}
</style>
