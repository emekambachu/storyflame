<template>
    <div
        class="max-w-[540px] py-8 min-h-dvh mx-auto font-normal bg-white text-black flex flex-col gap-8 items-center justify-between"
    >
        <a href="/">
            <img
                class="w-[393px]"
                src="@/assets/logo.svg"
            />
        </a>

        <div class="flex flex-col gap-8 w-full">
            <div class="flex flex-col gap-4 items-center">
                <h5 class="inline text-center text-black text-lg font-semibold">
                    Develop your story.
                    <br />
                    Refine your story with fire 🔥
                </h5>
                <span class="text-zinc-400 text-sm font-normal">
                    Please, enter your details to register
                </span>
            </div>
        </div>

        <Alert
            v-if="Object.keys(errors).length > 0"
            :classes="'bg-rose-100 border-rose-400 text-rose-700 px-4 tx-2'">
            <p v-for="(error, index) in Object.keys(errors)" :key="index">
                {{ error[0] }}
            </p>
        </Alert>

        <Alert
            :classes="'bg-green-100 border-green-400 text-green-700 px-4 tx-2'"
            v-if="submitted">
            Email submitted, Please check your email for a verification token
        </Alert>

        <Alert
            :classes="'bg-green-100 border-green-400 text-green-700'"
            v-else-if="emailVerified"
            type="success"
        >
            Your email has been verified, please please ad your full name
        </Alert>

        <div v-if="!submitted && !emailVerified" class="grid grid-cols-1 gap-4">
            <div>
                <input
                    class="w-full py-4 px-4 rounded-full text-base font-normal text-black bg-neutral-100 focus:outline-none"
                    type="email"
                    placeholder="Email"
                    v-model="form.email"
                />
                <p class="text-rose-600">
                    {{ errors.email ? errors.email[0] : '' }}
                </p>
            </div>
        </div>

        <div v-if="submitted && !emailVerified" class="grid grid-cols-1 gap-4">
            <div>
                <input
                    class="w-full py-4 px-4 rounded-full text-base font-normal text-black bg-neutral-100 focus:outline-none"
                    type="text"
                    placeholder="Email Token"
                    v-model="token"
                />
                <p class="text-rose-600">
                    {{ errors.token ? errors.token[0] : '' }}
                </p>
            </div>
        </div>

        <div v-if="submitted && emailVerified" class="flex flex-col gap-4 w-full">
            <div class="flex flex-col gap-4">
                <p class="text-green-600 text-center">
                    Completed, redirect user to update first and last name
                </p>
            </div>
        </div>

        <div class="mb-0 flex flex-col gap-2">
            <ButtonWithLoader
                :class="'w-full flex justify-center py-4 px-4 rounded-full text-base font-semibold text-white bg-orange-600 hover:bg-orange-600'"
                :loading="loading"
                @click=" !submitted && !emailVerified ? register() : submitToken()"
            >
                Register
            </ButtonWithLoader>

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
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import axios from 'axios';
import ButtonWithLoader from '@/components/forms/ButtonWithLoader.vue'
import router from '@/router'
import Alert from '@/components/forms/Alert.vue'

const referred_by_code = ref(router.currentRoute.value.query.referred_by_code);
const membership = ref(router.currentRoute.value.query.membership);

const form = reactive({
    email: '',
    referred_by_code: referred_by_code.value ? referred_by_code.value : '',
    membership: membership.value ? membership.value : '',
});

const token = ref('');

const loading = ref(false);
const errors = ref({});
const submitted = ref(false);
const emailVerified = ref(false);

const register = async () => {

    loading.value = true;
    console.log("FORM", form);

    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    await axios.post('/api/register/v2', form, {
        headers: {
            'Accept' : 'application/json',
        }
    }).then((response) => {
        if (response.data.success){
            submitted.value = true;
        }

    }).catch((error) => {
        if (error.response) {
            console.log(error.response);

            if (Object.keys(error.response?.data?.errors).length > 0) {
                errors.value = error.response?.data?.errors;
            }

            if (error.response?.data?.server_error) {
                errors.value.server_error = 'Server error. Please try again later or contact your admin.';
            }
        }
    });

    loading.value = false;
}

const submitToken = async () => {

    loading.value = true;
    console.log("EMAIL TOKEN", token.value);

    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    await axios.post('/api/register/verify',
        {
            token: token.value

        }, {
        headers: {
            'Accept' : 'application/json',
        }
    }).then((response) => {
        if (response.data.success){
            emailVerified.value = true;
        }

    }).catch((error) => {
        if (error.response) {
            console.log(error.response);

            if (Object.keys(error.response?.data?.errors).length > 0) {
                errors.value = error.response?.data?.errors;
            }

            if (error.response?.data?.server_error) {
                errors.value.server_error = 'Server error. Please try again later or contact your admin.';
            }
        }
    });

    loading.value = false;
}


</script>

<style scoped></style>
