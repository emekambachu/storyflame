<template>
    <split-view-layout>
        <div
            id="sign-in-container"
            style="box-shadow: 0px 4px 12px 0px rgba(0, 0, 0, 0.1)"
            class="flex flex-col items-center gap-10 border border-stone-200 bg-white rounded-lg px-6 py-12 font-normal text-black lg:min-w-[520px] lg:px-10 lg:py-40"
        >
            <div class="flex w-full flex-col gap-6">
                <div
                    id="sign-in-form"
                    class="w-full flex flex-col gap-10"
                >
                    <h2 class="w-full font-main text-2xl font-medium text-stone-950 select-none">
                        {{ formTitle }}
                    </h2>
                    <div
                        v-if="config?.otp_sent"
                        class="text-stone-600"
                    >
                        A passcode has been sent to your email.
                    </div>
                    <label
                        class="w-full text-center font-main text-xl font-medium text-black lg:text-left lg:text-2xl"
                        for="email"
                    >
                        <input
                            id="email"
                            v-model="credentials.email"
                            :disabled="config?.email || isLoading"
                            autocomplete="email"
                            class="block w-full border-b border-stone-400 bg-white py-3 text-base font-normal text-stone-950 focus:outline-none disabled:bg-blue-100 disabled:px-2 disabled:border-blue-300 disabled:text-blue-500"
                            placeholder="Enter your email"
                            @keydown.enter="signIn"
                            required
                            type="email"
                        />
                    </label>
                </div>

                <div
                    v-if="config?.email"
                    class="w-full flex flex-col gap-6"
                >
                    <label
                        v-if="config?.email && config?.otp_sent"
                        class="w-full text-xs uppercase font-semibold text-stone-300"
                        for="otp"
                    >
                        One-time passcode
                        <input
                            id="otp"
                            v-model="credentials.otp"
                            class="text-stone-950 block w-full border-b border-stone-400 bg-white text-base py-3 font-normal focus:outline-none placeholder:text-stone-400"
                            max="6"
                            min="6"
                            placeholder="Enter passcode sent to your email"
                            required
                            type="text"
                        />
                    </label>
<!--                    Add label and field for Referral Code, this is optional for the user, and only appears if user.email_verified_at is null and user.referred_by_code is null-->
                    <label
                        v-if="showReferralCode"
                        class="w-full text-xs uppercase font-semibold text-stone-300"
                        for="referral_code"
                    >
                        Optional Referral Code
                        <input
                            id="referral_code"
                            v-model="credentials.referred_by_code"
                            class="text-stone-950 block w-full border-b border-stone-400 bg-white text-base py-3 font-normal focus:outline-none placeholder:text-stone-400"
                            placeholder="Enter optional referral code"
                            required
                            type="text"
                        />
                    </label>
                </div>
            </div>

            <div class="flex w-full flex-col items-center gap-4">
                <button
                    class="flex w-full justify-center rounded-lg bg-stone-900 px-4 py-4 text-sm font-semibold text-stone-50 hover:bg-black hover:text-white select-none"
                    type="submit"
                    @click="signIn"
                >
                    {{ buttonTitle }}
                </button>

                <div
                    v-if="!config?.email"
                    class="inline text-sm font-normal text-stone-600"
                >
                    By continuing, you agree to our
                    <a
                        href="https://www.storyflame.io/legal-terms/"
                        target="_blank"
                        class="font-semibold hover:text-stone-900"
                    >
                        Terms of Service
                    </a>
                    and
                    <a
                        href="https://www.storyflame.io/legal-privacy/"
                        target="_blank"
                        class="font-semibold hover:text-stone-900"
                    >
                        Privacy
                    Policy
                    </a>
                    .
                </div>

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
    </split-view-layout>
</template>

<script lang="ts" setup>
import {computed, onMounted, onUnmounted, ref} from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter, useRoute } from 'vue-router'
import splitViewLayout from '@/layouts/splitViewLayout.vue'
import CrossedEye from '@/components/icons/CrossedEye.vue'
import { useLayoutControl} from "@/utils/useLayoutControl";

const props = defineProps<{
    query?: Record<string, string>
}>()

const auth = useAuthStore()
const credentials = ref({
    email: '',
    password: '',
    otp: '',
    referred_by_code: '',
    use_code: true,
})
const config = ref<Config | null>(null);
const router = useRouter()
const route = useRoute()
const queryParams = computed(() => props.query || route.query)
const { setNavVisibility, setFullWidth } = useLayoutControl()


const formTitle = computed(() => {
    // Ensure config.value is not undefined or null before accessing otp_sent
    if (config.value?.otp_sent) {
        return "Enter your passcode";
    }
    return "Enter your email to get started";
});

const buttonTitle = computed(() => {
    if (config.value?.otp_sent) {
        return "Verify Email Passcode";
    }
    return "Register or Sign in";
});

const showPassword = ref(false);
const isLoading = ref(false);

function signIn() {
    if (isLoading.value) {
        return
    }
    isLoading.value = true;
    if (!config.value?.email) {
        federate();
        return
    }

    login();
}

function federate() {
    console.log('Trying federate')
    auth.federate(credentials.value.email, credentials.value.referred_by_code, true).then((res) => {
        config.value = res.data
        isLoading.value = false
    }).catch(() => {
        isLoading.value = false
    })
}

function login() {
    console.log('Trying login')
    auth.login(credentials.value).then(() => {
        console.log('Trying auth.login')
        router.push({
            name: 'onboarding',
            query: { referral_code: credentials.value.referred_by_code || undefined }
        })
        isLoading.value = false
    }).catch(() => {
        isLoading.value = false
    })
}

const showReferralCode = computed(() => {
    return config.value.email && !config.value?.referred_by && !config.value?.email_verified_at
})

onMounted(() => {
    console.log('Mounted')
    console.log('Query params:', queryParams.value)
    credentials.value.referred_by_code = queryParams.value.referral_code as string || null
    // if (credentials.value.referral_code) {
    //     localStorage.setItem('referralCode', credentials.value.referral_code)
    // }
    console.log('Referral Code:', credentials.value.referred_by_code)

    setNavVisibility(false);
    setFullWidth(true);
})

onUnmounted(() => {
    setNavVisibility(true);
    setFullWidth(false);
})
</script>

<style scoped></style>
