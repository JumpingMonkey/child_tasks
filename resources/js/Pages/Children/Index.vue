<template>
    <div class="flex items-center justify-end m-2 p-2">
        
        <Link class="btn-primary">Add child </Link>
    </div>
    <Child v-for="child in children" :key="child.id" :child="child" class="mt-4"></Child>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import Child from "@/Pages/Children/Index/Components/Child.vue";

defineProps({
    children: Array,
})
</script>