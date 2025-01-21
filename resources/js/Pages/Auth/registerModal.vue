<script setup>
    // https://www.youtube.com/watch?v=_GygbuxiEnA&list=PL38wFHH4qYZXCW2rlBLNdHi5cv-v_qlXO&index=11&ab_channel=LearnwithJon
    import { Modal } from '@inertiaui/modal-vue'; // https://inertiaui.com/inertia-modal/docs/
    import { useForm } from '@inertiajs/vue3'; // useForm is for handling form submission


    // https://inertiajs.com/forms
    const form = useForm({
        name: null,
        email: null,
        password: null,
        password_confirmation: null, // laravel convention to auto match password and confirm password
    })

    const submit = () => {
        form.post("/register", {
            onError: () => form.reset('password', 'password_confirmation'), // reset the form if there is an error
        })
    };
</script>


<template>
    <Modal class="modal">
        <div class="modal">
            <h1>Register a new account</h1>

            <form @submit.prevent="submit">
                <label for="name">Name</label>
                <input type="text" v-model="form.name" name="name" placeholder="Enter your name">
                <small>{{ form.errors.name }}</small>

                <label for="email">Email</label>
                <input type="text" v-model="form.email" name="email" placeholder="Enter your email">
                <small>{{ form.errors.email }}</small>

                <label for="password">Password</label>
                <input type="password" v-model="form.password" name="password" placeholder="Enter your password">
                <small>{{ form.errors.password }}</small>

                <label for="password">Confirm Password</label>
                <input type="password" v-model="form.password_confirmation" name="password_confirm" placeholder="Confirm your password">

                <button type="submit" :disabled="form.processing">Register</button>
            </form>
        </div>
    </Modal>
</template>

<script>
</script>
