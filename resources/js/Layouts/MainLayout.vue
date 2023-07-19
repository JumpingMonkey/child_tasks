<template>
    <header class="border-b w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 bg-white">
        <div class="container mx-auto"></div>
        <nav class="p-4 flex items-center justify-between">
            <div class="text-lg font-medium">
                <!-- <Link :href="route('listing.index')">Listings</Link> -->
            </div>
            <div class="text-xl font-bold text-center text-indigo-600 dark:text-indigo-300 font-medium">
                <Link :href="route('index')">Child Tasker</Link>
            </div>
            <div v-if="user" class="flex items-center gap-4">
                <div class="text-sm text-gray-500 flex-none">{{user.name}}</div>
                <!-- <Link :href="route('listing.create')" class="btn-primary">+ New Listing</Link> -->
                <div>
                    <Link :href="route('logout')" method="delete" as="button">Logout</Link>
                </div>
            </div>
            <div v-else>
                <Link :href="route('login')">Sign-In</Link>
            </div>
        </nav>
    </header>


    <div class="flex h-full min-h-screen">
        <div class="w-full px-4 py-2 bg-gray-200">
            <div class="container mx-auto">
                <main class="container mx-auto p-4 w-full">
                    <div v-if="flashSuccess" class="mb-4 p-2 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-800">
                        {{ flashSuccess }}
                    </div>
                    <slot>Default</slot>
                </main>
            </div>
        </div>
    </div>
    <footer class="flex justify-center bg-white p-4 border-b-2">
        <div class="">Copyright</div>
    </footer>

</template>

<script setup>
import { computed } from 'vue'
import {Link, usePage} from '@inertiajs/vue3'

const flashSuccess = computed(
  () => usePage().props.flash.success,
)
const user = computed(
    () => usePage().props.user,
)
</script>

