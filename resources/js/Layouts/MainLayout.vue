<template>
    <header class="border-b w-full border-gray-200 bg-white">
        <nav class="p-4 flex items-center justify-between">
            <div class="text-xl font-bold text-center text-blue-600 font-medium">
                <!-- <Link :href="route('posts.index')">Admin Dashboard</Link> -->
            </div>
            <div class="">
                User
                <!-- <Link :href="route('posts.create')" class="btn-primary">+ Post</Link> -->
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
import {Link, usePage} from '@inertiajs/vue3'
import {computed} from 'vue'

const flashSuccess = computed(
  () => usePage().props.flash.success,
)

</script>
