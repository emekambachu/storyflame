<template>
    <div
        class="max-w-[540px] mx-auto mt-1 font-normal bg-white text-black flex flex-col gap-4 items-center"
    >
        <div class="relative w-full">
            <router-link
                to="/"
                class="absolute text-neutral-950 top-auto bottom-auto left-0"
            >
                <chevron-icon />
            </router-link>
            <h2 class="text-base font-normal text-center text-neutral-950">
                Register
            </h2>
        </div>
        <form
            class="w-full flex flex-col gap-3"
            @submit.prevent="register"
        >
            <div>
                <label
                    class="text-slate-400 text-sm font-bold"
                    for="name"
                >
                    name
                    <input
                        id="name"
                        v-model="name"
                        class="mt-1 font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your name"
                        required
                        type="text"
                    />
                </label>
            </div>
            <div>
                <label
                    class="text-slate-400 text-sm font-bold"
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
                        class="mt-1 font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter your password"
                        required
                        type="password"
                    />
                </label>
            </div>

            <div class="mt-8 flex flex-col gap-5">
                <button
                    class="w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-red-600 hover:bg-red-700"
                    type="submit"
                >
                    Register
                </button>
                <p class="inline text-neutral-950 text-sm font-normal">
                    By continuing, you agree to our
                    <a
                        class="text-red-600"
                        href="/service-terms"
                    >
                        Terms of Service
                    </a>
                    and
                    <a
                        class="text-red-600"
                        href="/privacy-police"
                    >
                        Privacy Policy
                    </a>
                    .
                </p>
            </div>
        </form>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import ChevronIcon from '../components/icons/ChevronIcon.vue'

const auth = useAuthStore()
const name = ref('')
const email = ref('')
const password = ref('')

function register() {
    auth.register({
        name: name.value,
        email: email.value,
        password: password.value,
    })
}
</script>

<style scoped></style>
