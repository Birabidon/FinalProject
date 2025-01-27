<script setup>
// https://www.youtube.com/watch?v=_GygbuxiEnA&list=PL38wFHH4qYZXCW2rlBLNdHi5cv-v_qlXO&index=11&ab_channel=LearnwithJon
import { useForm } from '@inertiajs/vue3'; // useForm is for handling form submission
import TextInput from '@/Components/TextInput.vue'; // import TextInput custom component


// https://inertiajs.com/forms
const form = useForm({
    email: null,
    password: null,
    remember: null,
})

const submit = () => {
    form.post("/login", {
        onError: () => form.reset('password', 'remember'), // reset password, remember if there is an error
    })
};
</script>


<template>
    <Head title="Login"/>

    <h1 class="title">Login to your account</h1>

    <div class="container w-2/4 mx-auto">
        <form @submit.prevent="submit">

            <TextInput
                name="email"
                type="email"
                v-model="form.email"
                :message="form.errors.email"
            />

            <TextInput
                name="password"
                type="password"
                v-model="form.password"
                :message="form.errors.password"
            />

            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <p class="text-slate-600">
                    Need an account?
                    <a href="/register" class="text-link">Register</a>
                </p>
            </div>

            <button type="submit" class="primary-btn" :disabled="form.processing">Login</button>
        </form>
    </div>
</template>

<style scoped>
</style>
