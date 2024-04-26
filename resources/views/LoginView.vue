<template>
    <div
        class="max-w-[540px] mx-auto mt-10 text-black flex flex-col gap-4 items-center"
    >
        <div class="relative w-full">
            <router-link
                class="absolute text-neutral-950 top-auto bottom-auto left-0"
                to="/"
            >
                <chevron-icon />
            </router-link>
            <h2 class="text-base font-normal text-center text-neutral-950">Login</h2>
        </div>
        <form
            class="w-full flex flex-col gap-4"
            @submit.prevent="login"
        >
            <div>
                <label
                    class="text-slate-400 text- font-bold"
                    for="email"
                >
                    email
                    <input
                        id="email"
                        v-model="email"
                        class="mt-1 font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your email"
                        required
                        type="email"
                    />
                </label>
            </div>
            <div>
                <label
                    class="text-slate-400 text-sm font-bold"
                    for="password"
                >
                    password
                    <input
                        id="password"
                        v-model="password"
                        class="mt-1 font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your password"
                        required
                        type="password"
                    />
                </label>
            </div>
            <button
                class="mt-8 w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-red-600 hover:bg-red-700"
                type="submit"
            >
                Sign In
            </button>
        </form>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import ChevronIcon from '../components/icons/ChevronIcon.vue'

const auth = useAuthStore()
const email = ref('')
const password = ref('')

function login() {
    auth.login({
        email: email.value,
        password: password.value,
    })
}
</script>

<style scoped></style>
