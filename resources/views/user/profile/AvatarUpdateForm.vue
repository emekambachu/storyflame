<script setup lang="ts">
import { defineProps, onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import ButtonWithLoader from '@/components/forms/ButtonWithLoader.vue'
import Alert from '@/components/forms/Alert.vue'
import UserIcon from '@/components/icons/UserIcon.vue'
import validationService from '@/utils/validation-service'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})

const form = reactive({
    avatar: null,
});

const submitted = ref(false);
const loading = ref(false);
const errors = ref({});
const avatarPreview = ref(null);

const uploadAvatar = (event) => {
    let validateFileType = validationService.validateFileType(event.target.files[0], ['jpg', 'jpeg', 'png']);
    if(!validateFileType){
        errors.value['avatar'] = ['Incorrect file format. only jpg, jpeg, png'];
        form.avatar = null;
        return false;
    }

    let validateFileSize = validationService.validateFileSize(event.target.files[0], 2000000);
    if(!validateFileSize){
        errors.value['avatar'] = ['File too large, 2mb max'];
        form.avatar = null;
        return false;
    }

    //Assign image and path to this variable
    form.avatar = event.target.files[0];
    avatarPreview.value = URL.createObjectURL(event.target.files[0]);
    errors.value['avatar'] = [];
}

const updateAvatar = async () => {
    // Delete all errors
    Object.keys(errors.value).forEach(function(key) {
        delete errors.value[key];
    });

    submitted.value = false;
    loading.value = true;

    let formData = new FormData();
    formData.append('avatar', form.avatar);

    await axios.post('/api/user/avatar/update', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Accept' : 'application/json',
        }

    }).then((response) => {
        if (response.data.success){
            submitted.value = true;
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
        loading.value = false;
    });
    loading.value = false;
}

onMounted(() => {
    console.log("AVATAR UPDATE FORM", props.user);
});
</script>

<template>
    <div class="flex flex-col gap-6 w-full mb-auto mt-0">
        <Alert :classes="'bg-green-100 border-green-400 text-green-700 text-center'" v-if="submitted">
            Image updated
        </Alert>
        <div>
            <label for="avatar-input" class="cursor-pointer">
                <img
                    v-if="user.avatar && !avatarPreview"
                    :src="user.avatar"
                    alt="avatar"
                    class="rounded-full w-2/3 mx-auto object-cover object-center"
                />
                <img
                    v-else-if="avatarPreview"
                    :src="avatarPreview"
                    alt="avatar"
                    class="rounded-full w-2/3 mx-auto object-cover object-center"
                />
                <user-icon
                    v-else-if="!user.avatar && !avatarPreview"
                    class="text-neutral-400 w-2/3 mx-auto object-cover object-center"
                />
            </label>
            <div class="relative">
                <input
                    @change="uploadAvatar"
                    type="file"
                    id="avatar-input"
                    class="hidden"
                    accept="image/*"
                    required
                />
            </div>
            <p v-if="errors.avatar" class="text-rose-600">
                {{ errors.avatar[0] }}
            </p>
        </div>

        <div class="flex flex-col items-center w-full gap-2 mt-auto mb-0">
            <ButtonWithLoader
                :class="'text-white bg-orange-600 hover:bg-orange-700'"
                @click="updateAvatar"
                :loading="loading">
                Update
            </ButtonWithLoader>
        </div>
    </div>
</template>
