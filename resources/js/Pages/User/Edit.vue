<script setup>
import {defineProps, ref, watch} from 'vue';
import Avatar from '@/Components/Avatar.vue';
import TextInput from '@/Components/TextInput.vue';
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps(
    {
        user: Object
    }
);

// https://inertiajs.com/forms
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: null,
    password_confirmation: null, // laravel convention to auto match password and confirm password
    avatar: null
});

let preview = ref(null);
let isDisabled = ref(true);

watch(form, (newForm) => {
    isDisabled.value =  !(newForm.name !== props.user.name ||
        newForm.email !== props.user.email ||
        newForm.password &&
        newForm.password_confirmation ||
        newForm.avatar);
});

const updateAvatar = (e) => {
    form.avatar = e.target.files[0]; // get the first file from the file input
    preview.value = URL.createObjectURL(form.avatar); // create a url for the file
};

const deleteUser = () => {
    if (confirm('Are you sure you want to delete your account?')) {
        router.delete(`/users/${props.user.id}`);
    }
};

const submit = () => {
    if (form.name === props.user.name && form.email === props.user.email && form.password === null
        && form.password_confirmation === null && form.avatar === null) {
        return;
    }

    if (form.name === props.user.name) {
        form.name = null; // if name is not changed, set it to null
    }

    if (form.email === props.user.email) {
        form.email = null; // if email is not changed, set it to null
    }

    form.post(`/users/${props.user.id}`, {
        onError: () => {
            form.name = form.reset('name'); // reset name if there is an error
            form.email = form.reset('email'); // reset email if there is an error
        }, // reset passwords if there is an error
    })
};

</script>

<template>
    <div class="profile-page">
        <div class="cover">
<!--            user.cover not implemented yet -->
            <img :src="user.cover ? ('/storage/' + user.cover) : ('/storage/avatars/default.jpg')" alt="cover" class="cover-image">
        </div>

        <div class="profile-header">
            <div class="left-section">
                <div class="avatar-container">
                    <input type="file" id="avatar" @input="change" hidden>

                    <Avatar :path="preview ? preview : user.avatar" alt="Avatar preview" :url="!!preview"/>
                </div>
                <h1>{{ user.name }}</h1>
            </div>
        </div>

        <div class="edit-form">
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <input type="file" id="avatar" @change="updateAvatar">
                    <p class="error mt-2">{{ form.errors.avatar}}</p>
                </div>

                <TextInput name="name" v-model="form.name" :message="form.errors.name"/>

                <TextInput name="email" type="email" v-model="form.email" :message="form.errors.email"/>

                <TextInput name="password" type="password" v-model="form.password" :message="form.errors.password"/>

                <TextInput name="Confirm Password" type="password" v-model="form.password_confirmation"/>

                <div class="form-buttons">
                    <button type="submit" class="save-button"  :disabled="form.processing || isDisabled">Save Changes</button>
                    <button type="button" class="delete-button" @click="deleteUser">Delete User</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.profile-page {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.cover {
    width: 100%;
    height: 300px;
    overflow: hidden;
    position: relative;
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 20px;
    margin-top: -50px;
    border-radius: 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    width: 100%;
}

.left-section {
    display: flex;
    align-items: center;
    gap: 20px;
}

.avatar-container {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #fff;
    position: relative;
    top: -60px;
    box-shadow: 0 0 0 4px #fff;
}

.avatar-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

h1 {
    font-size: 32px;
    color: #333;
}

.edit-form {
    background-color: #fff;
    padding: 20px;
    margin-top: 10px;
    border-radius: 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    flex-grow: 1;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-buttons {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.save-button, .delete-button {
    background-color: #1877f2;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s;
    border: none;
    cursor: pointer;
}

.save-button:hover {
    background-color: #165dbb;
}

.save-button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.delete-button {
    background-color: #ff3b3b;
}

.delete-button:hover {
    background-color: #d32f2f;
}
</style>
