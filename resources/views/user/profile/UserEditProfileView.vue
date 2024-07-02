<template>
    <page-navigation-layout fixed transparent>
        <div class="mx-auto flex h-full grow w-full max-w-md flex-col items-center">
            <div class="py-8 my-auto mx-auto font-normal rounded-lg bg-stone-100 p-8 text-black flex flex-col gap-8 items-center">
                <div class="flex justify-between w-full">
                    <button
                        class="px-4 py-2 rounded-md"
                        :class="[activeTab === 'bio' ? 'bg-orange-600 text-white' : 'bg-stone-200 text-black']"
                        @click="activeTab = 'bio'"
                    >
                        Bio
                    </button>
                    <button
                        class="px-4 py-2 rounded-md"
                        :class="[activeTab === 'bio' ? 'bg-orange-600 text-white' : 'bg-stone-200 text-black']"
                        @click="activeTab = 'password'"
                    >
                        Password
                    </button>
                    <button
                        class="px-4 py-2 rounded-md"
                        :class="[activeTab === 'bio' ? 'bg-orange-600 text-white' : 'bg-stone-200 text-black']"
                        @click="activeTab = 'avatar'"
                    >
                        Avatar
                    </button>
                </div>

                <div class="w-full">
                    <div v-if="activeTab === 'bio'">
                        <bio-update-form />
                    </div>

                    <div v-if="activeTab === 'password'">
                        <password-update-form />
                    </div>

                    <div v-if="activeTab === 'avatar'">
                        <avatar-update-form />
                    </div>
                </div>
            </div>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { onBeforeMount, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import BioUpdateForm from './BioUpdateForm.vue'
import PasswordUpdateForm from './PasswordUpdateForm.vue'
import AvatarUpdateForm from './AvatarUpdateForm.vue'

const activeTab = ref('bio');
const { user, getUser } = useAuthStore()

onBeforeMount(() => {
   console.log("Logged User", user);
   console.log("Logged User 2", getUser());
});

</script>

<style scoped></style>
