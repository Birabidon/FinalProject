<script setup>
    // https://www.youtube.com/watch?v=_GygbuxiEnA&list=PL38wFHH4qYZXCW2rlBLNdHi5cv-v_qlXO&index=11&ab_channel=LearnwithJon
    import { useForm } from '@inertiajs/vue3'; // useForm is for handling form submission
    import TextInput from '@/Components/TextInput.vue'; // import TextInput custom component


    // https://inertiajs.com/forms
    const form = useForm({
        name: null,
        email: null,
        password: null,
        password_confirmation: null, // laravel convention to auto match password and confirm password
    })

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
            <TextInput name="name" v-model="form.name" :message="form.errors.name"/>

            <TextInput name="email" type="email" v-model="form.email" :message="form.errors.email"/>

            <TextInput name="password" type="password" v-model="form.password" :message="form.errors.password"/>

            <TextInput name="Confirm Password" type="password" v-model="form.password_confirmation"/>

            <p class="text-slate-600 mb-2">Already a user? <a href="/login" class="text-link">Login</a></p>
            <button type="submit" class="primary-btn" :disabled="form.processing">Register</button>
        </form>
    </div>
</template>

