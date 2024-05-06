<template>
    <div
        class="max-w-[540px] h-full mx-auto mt-1 font-normal bg-white text-black flex flex-col gap-4 items-center"
    >
        <div class="relative w-full">
            <a
                class="absolute text-neutral-950 top-auto bottom-auto left-0"
                href="/"
            >
                <chevron />
            </a>
            <h2 class="text-base font-normal text-center text-neutral-950">
                Login
            </h2>
        </div>
        <form
            class="w-full flex flex-col gap-3 h-full"
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
                        v-model="credentials.email"
                        autocomplete="email"
                        class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your email"
                        required
                        type="email"
                    />
                </label>
            </div>
            <div v-if="federated === credentials.email">
                <label
                    v-if="pwd"
                    class="text-slate-400 text-sm font-bold"
                    for="password"
                >
                    password
                    <input
                        id="password"
                        v-model="credentials.password"
                        class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your password"
                        required
                        type="password"
                    />
                </label>
                <label
                    v-else
                    class="text-slate-400 text-sm font-bold"
                    for="otp"
                >
                    one-time passcode
                    <input
                        id="otp"
                        v-model="credentials.otp"
                        class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        max="6"
                        min="6"
                        placeholder="Enter passcode sent to your email"
                        required
                        type="text"
                    />
                </label>
            </div>
            <button
                class="mt-auto mb-0 w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-red-600 hover:bg-red-700"
                type="submit"
            >
                Sign In
            </button>
            <p v-if="federated === credentials.email && config?.otp_sent">
                A passcode has been sent to your email.
            </p>
        </form>
        <pre>{{ credentials }}</pre>
        <pre>{{ config }}</pre>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import Chevron from '../components/icons/Chevron.vue'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const credentials = ref({ email: '', password: '', otp: '' })
const pwd = ref(false)
const federated = ref<string | undefined>(undefined)
const config = ref({})
const router = useRouter()

function login() {
    // if not federated or federated email is different
    if (!federated.value || federated.value !== credentials.value.email) {
        const email = credentials.value.email
        auth.federate(email).then((res) => {
            federated.value = email
            config.value = res.data
        })
        return
    }

    auth.login(credentials.value).then(() => {
        router.push('/')
    })
}
</script>

<style scoped></style>
