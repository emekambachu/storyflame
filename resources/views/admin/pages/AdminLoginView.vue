<template>
    <div class="flex items-center justify-center h-screen bg-stone-100">
        <div class="bg-white rounded-lg shadow-lg p-8">

            <img src="/images/logo.svg">

            <h2 class="text-2xl font-bold mb-6">Log In</h2>

            <p class="text-red-500 text-xs italic" v-if="errors.error_message">
                {{ errors.error_message }}
            </p>

            <div class="text-center" v-if="loading">
                <div role="status">
                    <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <form @submit.prevent="submitLogin">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="email">
                        Email
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-stone-100"
                        type="email"
                        placeholder="example@mail.com"
                        v-model="form.email"
                    />
                    <p class="text-red-500 text-sm text-center"
                       v-if="errors.email">
                        {{ errors.email[0] }}
                    </p>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="password">
                        Password
                    </label>
                    <input
                        class="shadow bg-stone-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        id="password"
                        type="password"
                        placeholder="Enter Password"
                        v-model="form.password"
                    />
                    <p class="text-red-500 text-sm text-center"
                       v-if="errors.password">
                        {{ errors.password[0] }}
                    </p>
                    <a class="text-gray-700" href="">
                        <p class="text-sm text-blue-500 hover:text-blue-800">Forgot Password?</p>
                    </a>
                </div>
                <div class="flex items-center justify-center">
                    <button
                        class="bg-black hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline w-2/3"
                        type="submit"
                    >
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import axios from 'axios';
import validationService from '@/utils/validation-service'
import router from '@/router/admin-routes'

let errors = reactive({});
const loading = ref(false);

const form = reactive({
    email: '',
    password: '',
});

const submitLogin = async () => {

    loading.value = true;
    localStorage.removeItem("story-flame-admin");
    validationService.deleteErrorsInObject(errors, null, true);

    try {
        const response = await axios.post('/api/admin/login', form,
            {
                headers: {"Accept": "application/json"}
            });

        if(response.data.success) {
            // Store relevant user details in local storage
            const user = {
                name: response.data.user.first_name + ' ' + response.data.user.last_name,
                email: response.data.user.email,
                token: response.data.token,
                authenticated: true,
            };
            // Store logged-in user in local storage
            localStorage.setItem('story-flame-admin', JSON.stringify(user));

            console.log(response.data.user);
            window.location.href = '/admin/achievements';
            // router.push('admin/achievements');
        }

    } catch (error) {

        if (error?.response?.status === 401 || error?.response?.data.success === false) {
            if(error.response.data.server_error) {
                console.log("Server error", error.response.data.error_message);
                console.log("Server error", error.response.data.error_message);
                errors.server_error = 'Oh oh, error occurred. please contact the admin';
            }

            if (error.response?.data?.errors) {
                console.log("Data Errors", error.response?.data?.errors);
                // if errors exist in response, check if it's an object and convert to array
                errors = error.response?.data?.errors;
                console.log("Errors", errors);
            }
        }
    }

    loading.value = false;

}
</script>
