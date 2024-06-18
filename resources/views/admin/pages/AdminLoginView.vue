<template>
    <div class="flex items-center justify-center h-screen bg-stone-100">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Log In</h2>

<!--            <p class="text-red-500 text-xs italic" v-if="errors.value.error_message">-->
<!--                {{ errors.value.error_message }}-->
<!--            </p>-->

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
                    <p class="text-red-500 text-xs italic"
                       v-if="errors?.value.email">
                        {{ errors?.value.email }}
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
                    <p class="text-red-500 text-xs italic"
                       v-if="errors?.value.password">
                        {{ errors?.value.password }}
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

let errors = reactive({});
const loading = ref(false);

const form = reactive({
    email: '',
    password: '',
});

const submitLogin = async () => {

    loading.value = false;
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
            window.location.href = '/admin/dashboard';
        }

    } catch (error) {
        if (error?.response?.data?.success === false) {
            if(error.response.data.server_error) {
                console.log("Server error", error.response.data.error_message);
                console.log("Server error", error.response.data.error_message);
                errors.server_error = 'Oh oh, error occurred. please contact the admin';
            }

            if (error.response?.data?.errors) {
                // if errors exist in response, check if it's an object and convert to array
                errors = error.response?.data?.errors;
                console.log("Errors", errors);
            }
        }
    }

}
</script>
