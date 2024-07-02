<script setup lang="ts">
import { onBeforeMount, onMounted, reactive, ref } from 'vue'
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
    first_name: props.user ? props.user.first_name : '',
    last_name: props.user ? props.user.last_name : '',
    bio:    props.user ? props.user.bio : '',
});

const submitted = ref(false);
const loading = ref(false);
const errors = ref({});

const updateBio = async () => {

    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    await axios.post('/api/user/bio/update', form, {
        headers: {
            'Accept' : 'application/json',
        }
    }).then((response) => {
        if (response.data.success){
            submitted.value = true;
        }

    }).catch((error) => {

        if(error.response && [401, 402, 422].includes(error.response.status)){
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

onMounted(() => {

});

</script>

<template>
<div>

    <form>

        <div class="flex flex-col gap-6 w-full mb-auto mt-0">

            <Alert :classes="'bg-green-100 border-green-400 text-green-700'" v-if="submitted">
                Submitted
            </Alert>

            <div>
                <label
                    class="text-black text-base font-bold w-full"
                    for="password"
                >
                    First Name
                </label>
                <input
                    id="password"
                    v-model="form.first_name"
                    type="text"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="First Name"
                    required
                />
                <p class="text-red-500 text-center text-sm" v-if="errors.first_name">
                    {{ errors.first_name[0] }}
                </p>
            </div>

            <div>
                <label
                    class="text-black text-base font-bold w-full"
                    for="password"
                >
                    Last Name
                </label>
                <input
                    v-model="form.last_name"
                    type="text"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Last Name"
                    required
                />
                <p class="text-red-500 text-center text-sm" v-if="errors.last_name">
                    {{ errors.last_name[0] }}
                </p>
            </div>

            <div>
                <label
                    class="text-black text-base font-bold w-full"
                    for="bio"
                >
                    Bio
                </label>
                <textarea
                    id="bio"
                    v-model="form.bio"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Enter your bio"
                    required
                >
                </textarea>
                <p class="text-red-500 text-center text-sm" v-if="errors.bio">
                    {{ errors.bio[0] }}
                </p>
            </div>

        </div>

        <div class="flex flex-col items-center w-full mt-2 mb-0">
           <ButtonWithLoader
               :class="'text-white bg-orange-600 hover:bg-orange-700'"
               @click="updateBio"
               :loading="loading">
               Update Bio
              </ButtonWithLoader>
        </div>
    </form>

</div>
</template>

<style scoped>

</style>
