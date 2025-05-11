<script setup>
    // https://www.youtube.com/watch?v=_GygbuxiEnA&list=PL38wFHH4qYZXCW2rlBLNdHi5cv-v_qlXO&index=11&ab_channel=LearnwithJon
    import { useForm } from '@inertiajs/vue3'; // useForm is for handling form submission
    import TextInput from '@/Components/TextInput.vue';
    import Avatar from "@/Components/Avatar.vue"; // import TextInput custom component


    // https://inertiajs.com/forms
    const form = useForm({
        name: null,
        email: null,
        password: null,
        password_confirmation: null, // laravel convention to auto match password and confirm password
        avatar: null,
        preview: null,
    })

    const change = (e) => {
        if (e.target.files.length > 0) { // check if there are files in the input
            form.avatar = e.target.files[0]; // get the first file from the file input
            form.preview = URL.createObjectURL(form.avatar); // create a url for the file
        }
    }

    const submit = () => {
        form.post("/register", {
            onError: () => form.reset('password', 'password_confirmation'), // reset passwords if there is an error
        })
    };

</script>


<template>
    <Head title="Register"/>

    <h1 class="title">Register a new account</h1>

    <div class="w-2/4 mx-auto">
        <form @submit.prevent="submit">
            <div class="grid place-items-center">
                <div class="relative w-28 h-28 rounded-full overflow-hidden border border-slate-300  bg-gray-100">
                    <label class="absolute inset-0 grid content-end cursor-pointer" for="avatar">
                        <span class="bg-white/70 pb-2 text-center">Avatar</span>
                    </label>
                    <input type="file" id="avatar" @input="change" hidden>

                    <Avatar
                        :path="form.preview"
                        alt="Avatar preview"
                        :url="true"
                        class="w-full h-full object-cover object-center"/>
<!--                    <img :src="form.preview ?? 'storage/avatars/default.jpg'" alt="preview">-->
                </div>

                <p class="error mt-2">{{ form.errors.avatar}}</p>
            </div>
            <TextInput name="name" v-model="form.name" :message="form.errors.name"/>

            <TextInput name="email" type="email" v-model="form.email" :message="form.errors.email"/>

            <TextInput name="password" type="password" v-model="form.password" :message="form.errors.password"/>

            <TextInput name="Confirm Password" type="password" v-model="form.password_confirmation"/>

            <p class="text-slate-600 mb-2">Already a user? <a href="/login" class="text-link">Login</a></p>
            <button type="submit" class="primary-btn" :disabled="form.processing">Register</button>
        </form>
    </div>
</template>

