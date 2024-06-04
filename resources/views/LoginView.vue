<template>
    <div
        class="max-w-[540px] py-8 min-h-dvh mx-auto font-normal bg-white text-black flex flex-col gap-8 items-center"
    >
        <a href="/">
            <img
                class="w-[393px]"
                src="@/assets/logo.svg"
            />
        </a>

        <div class="flex flex-col gap-6 w-full mb-auto mt-0">
            <label
                class="text-black text-base font-bold w-full"
                for="email"
            >
                Email
                <input
                    id="email"
                    v-model="credentials.email"
                    autocomplete="email"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Enter your email"
                    required
                    type="email"
                />
            </label>

            <div
                v-if="federated === credentials.email"
                class="w-full"
            >
                <label
                    v-if="pwd"
                    class="text-black text-base font-bold w-full"
                    for="password"
                >
                    Password
                    <div class="relative">
                        <input
                            id="password"
                            v-model="credentials.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                            placeholder="Enter your password"
                            required
                        />

                        <button
                            :class="
                                !showPassword
                                    ? 'text-neutral-700'
                                    : 'text-orange-600'
                            "
                            class="absolute top-5 right-0"
                            @click="showPassword = !showPassword"
                        >
                            <crossed-eye />
                        </button>
                    </div>
                </label>
                <label
                    v-else
                    class="text-black text-base font-bold w-full"
                    for="otp"
                >
                    One-time passcode
                    <input
                        id="otp"
                        v-model="credentials.otp"
                        class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                        max="6"
                        min="6"
                        placeholder="Enter passcode sent to your email"
                        required
                        type="text"
                    />
                </label>
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
            <p class="inline text-neutral-950 text-sm font-normal">
                Don't have an account?
                <router-link
                    :to="{ name: 'register' }"
                    class="text-red-600"
                >
                    Register
                </router-link>
            </p>
            <p v-if="federated === credentials.email && config?.otp_sent">
                A passcode has been sent to your email.
            </p>

            <!--            <span class="text-neutral-700 text-xs font-normal text-center mt-2">-->
            <!--                Or-->
            <!--            </span>-->

            <!--            <div class="flex items-center gap-8 mt-4">-->
            <!--                <img-->
            <!--                    class="w-6 h-6 shrink-0"-->
            <!--                    src="@/assets/images/google_logo.svg"-->
            <!--                />-->
            <!--                <img-->
            <!--                    class="w-6 h-6 shrink-0"-->
            <!--                    src="@/assets/images/facebook_logo.svg"-->
            <!--                />-->
            <!--                <img-->
            <!--                    class="w-6 h-6 shrink-0"-->
            <!--                    src="@/assets/images/apple_logo.svg"-->
            <!--                />-->
            <!--            </div>-->
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import CrossedEye from '../components/icons/CrossedEye.vue'

const auth = useAuthStore()
const credentials = ref({ email: '', password: '', otp: '' })
const pwd = ref(false)
const federated = ref<string | undefined>(undefined)
const config = ref({})
const router = useRouter()

const showPassword = ref(false)

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
        router.push({
            name: 'onboarding'
        })
    })
}
</script>

<style scoped></style>
