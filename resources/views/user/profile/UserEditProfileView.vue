<template>

    <page-navigation-layout fixed transparent>
        <div class="mx-auto flex h-full grow w-full max-w-md flex-col items-center">
            <div class="py-8 my-auto mx-auto font-normal rounded-lg bg-stone-200 p-8 text-black flex flex-col gap-8 items-center">

                <div class="flex flex-col gap-6 w-full mb-auto mt-0">
                    <div>
                        <label
                            class="text-black text-base font-bold w-full"
                            for="email">
                            Name
                        </label>
                        <input
                            v-model="form.name"
                            autocomplete="name"
                            class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                            placeholder="Enter Your Name"
                            required
                            type="text"
                        />
                    </div>

                    <div>
                        <label
                            class="text-black text-base font-bold w-full"
                            for="email">
                            Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            autocomplete="email"
                            class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                            placeholder="Enter your email"
                            required
                            type="email"
                        />
                    </div>

                    <div>
                        <label
                            class="text-black text-base font-bold w-full"
                            for="password"
                        >
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                            placeholder="Enter your password"
                            required
                        />

                    </div>
                </div>

                <div class="flex flex-col items-center w-full gap-2 mt-auto mb-0">
                    <button
                        class="w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700"
                        type="submit"
                        @click="login"
                    >
                        Sign In
                    </button>
                </div>

            </div>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import CrossedEye from '../../../components/icons/CrossedEye.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const showPassword = ref(false)

function login() {

}
</script>

<style scoped></style>
