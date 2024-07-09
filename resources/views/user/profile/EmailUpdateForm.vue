<script setup lang="ts">
import { defineProps, onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import ButtonWithLoader from '@/components/forms/ButtonWithLoader.vue'
import Alert from '@/components/forms/Alert.vue'

const props = defineProps({
    user: {
        type: String,
        required: true,
    },
})

const form = reactive({
    email: '',
    // email_confirmation: '',
});

const submitted = ref(false);
const tokenSubmitted = ref(false);
const loading = ref(false);
const errors = ref({});
const emailToken = ref('');

const updateEmail = async () => {
    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    await axios.post('/api/user/email/update', form, {
        headers: {
            'Accept' : 'application/json',
        }
    }).then((response) => {
        if (response.data.success){
            submitted.value = true;
        }

    }).catch((error) => {

        console.log("AXIOS ERROR", error);
        return false;

        if(error.response){
            console.log(error.response);

            if(Object.keys(error.response?.data?.errors).length > 0){
                errors.value = error.response?.data?.errors;
            }

            if(error.response?.data?.server_error){
                errors.value.server_error = 'Server error. Please try again later or contact your admin.';
            }
        }

        console.log(error);
    });
    loading.value = false;
}

const confirmEmailToken = async () => {
    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    tokenSubmitted.value = false;
    loading.value = true;

    await axios.post('/api/user/email/token',
        {
                token: emailToken.value,
    }, {
        headers: {
            'Accept' : 'application/json',
        }
    }).then((response) => {
        if (response.data.success){
            tokenSubmitted.value = true;
        }

    }).catch((error) => {
        if(error.response){
            console.log(error.response);

            if(Object.keys(error.response?.data?.errors).length > 0){
                errors.value = error.response?.data?.errors;
            }

            if(error.response?.data?.server_error){
                errors.value.server_error = 'Server error. Please try again later or contact your admin.';
            }
        }

        console.log(error);
    });
    loading.value = false;
}

</script>

<template>
    <div>

        <form>
            <div class="flex flex-col gap-6 w-full mb-auto mt-0">

                <Alert :classes="'bg-green-100 border-green-400 text-green-700 text-center'" v-if="submitted && !tokenSubmitted">
                    Email update request sent, please check your email for the confirmation code.
                </Alert>

                <Alert :classes="'bg-green-100 border-green-400 text-green-700 text-center'" v-if="submitted && tokenSubmitted">
                    Email Successfully Updated.
                </Alert>

                <div v-if="!submitted && !tokenSubmitted">
                    <div>
                        <label
                            class="text-black text-base font-bold w-full"
                            for="email"
                        >
                            New Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                            required
                        />
                        <p class="text-red-500 text-center text-sm" v-if="errors.email">
                            {{ errors.email[0] }}
                        </p>
                    </div>
                </div>

                <div v-else>
                    <label
                        class="text-black text-base font-bold w-full"
                        for="email"
                    >
                        Email Confirmation Code
                    </label>
                    <input
                        id="email"
                        v-model="emailToken"
                        type="password"
                        class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                        required
                    />
                    <p class="text-red-500 text-center text-sm" v-if="errors.email">
                        {{ errors.email[0] }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-center w-full mt-2 mb-0">
                <ButtonWithLoader
                    :class="'text-white bg-orange-600 hover:bg-orange-700'"
                    @click.prevent="!submitted && !tokenSubmitted ? updateEmail() : confirmEmailToken()"
                    :loading="loading">
                    Update
                </ButtonWithLoader>
            </div>
        </form>

    </div>
</template>

<style scoped>

</style>
