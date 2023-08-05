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
                <div v-if="!user.is_parent" class="text-sm text-gray-500 flex-none">Coins: {{user.coins}}</div>
                <!-- <Link :href="route('listing.create')" class="btn-primary">+ New Listing</Link> -->
                <div>
                    <Link :href="route('logout')" method="delete" as="button">Logout</Link>
                </div>
            </div>
            <div v-else class="flex items-center gap-4">
                <Link :href="route('user-account.create')">Register</Link>
                <Link :href="route('login')">Sign-In</Link>
            </div>
        </nav>
    </header>


    <div class="flex h-full min-h-screen">
        <div class="w-full px-4 py-2 bg-gray-200">
            <div class="container mx-auto">
                <main class="container mx-auto px-4 w-full">
                    <div v-if="flashSuccess" class="mb-4 p-2 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-800">
                        {{ flashSuccess }}
                    </div>
                    <div v-if="user">
                        <div v-if="user?.is_parent" class="flex items-center justify-center space-x-5 py-4 mb-4 border-b-2 border-gray-300">
                        <Link :href="route('parent.children.get')" class="submenu-items">Your Children</Link>
                        <Link :href="route('parent.tasks.index')" class="submenu-items">Children's tasks</Link>
                        <Link :href="route('parent.rewards.index')" class="submenu-items">Rewords store</Link>
                    </div>
                    <div v-else-if="!user?.is_parent" class="flex items-center justify-center space-x-5 py-4 mb-4 border-b-2 border-gray-300">
                        <Link :href="route('child.parent.index')" class="submenu-items">Your Parent</Link>
                        <Link :href="route('child.tasks.index')" class="submenu-items">Tasks</Link>
                        <Link :href="route('child.rewards.index')" class="submenu-items">Rewords store</Link>
                    </div>
                    </div>
                    
                    <slot >Default</slot>
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

