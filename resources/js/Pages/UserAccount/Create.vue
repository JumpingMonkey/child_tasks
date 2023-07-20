<template>
    <form @submit.prevent="register">
    <div class="w-1/2 mx-auto">
        <div class="flex items-center justify-around">
            <div class="">
                <label for="parent" class="radio-label mb-2 mr-5">Parent</label>
                <input v-model="form.is_parent" checked type="radio" name="is_parent" value="1" id="parent" class="mr-5 checked:bg-blue-500" />
            </div>
            <div class="">
                <label for="child" class="radio-label mb-2 mr-5">Child</label>
                <input v-model="form.is_parent" type="radio" name="is_parent" value="0" id="child" class="checked:bg-blue-500" />
            </div>
            
            <!-- <div class="input-error" v-if="form.errors.is_parent">{{ form.errors.is_parent }}</div> -->
        </div>
        <div class="mt-4">
            <label for="name" class="label">Name</label>
            <input v-model="form.name" type="text" id="name" class="input" />
            <div class="input-error" v-if="form.errors.name">{{ form.errors.name }}</div>
        </div>
        <div class="mt-4">
            <label for="email" class="label">E-mail</label>
            <input v-model="form.email" type="text" id="email" class="input" />
            <div class="input-error" v-if="form.errors.email">{{ form.errors.email }}</div>
        </div>
        <div class="mt-4">
            <label for="password" class="label">Password</label>
            <input v-model="form.password" type="password" id="password" class="input" />
            <div class="input-error" v-if="form.errors.password">{{ form.errors.password }}</div>
        </div>
        <div class="mt-4">
            <label for="password_confirmation" class="label">Confirm password</label>
            <input v-model="form.password_confirmation" type="password" id="password_confirmation" class="input" />
            <!-- <div class="input-error" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</div> -->
        </div>
        <div class="mt-4">
            <button class="btn-primary w-full" type="submit">Create Account</button>
            <div class="mt-4 text-center">
                    <Link :href="route('login')" class="text-sm text-gray-500">
                        Already have an account? Click here
                    </Link>
                </div>

        </div>


    </div>
</form>
</template>


<script setup>
import { router, useForm, Link } from '@inertiajs/vue3'


const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    is_parent: true,
})


const register = () => form.post(route('user-account.store'))
</script>