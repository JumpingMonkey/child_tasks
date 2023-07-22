<template>
    <div class="flex flex-col items-center justify-center gap-4">
        <div>Your code:</div>
        <div class="text-2xl">{{ code.code }}</div>
        <div class="">Send code above to your child.</div>
    </div>
    <div class="flex items-center justify-center my-4">
        <button class="btn-primary w-1/2" @click="goBack">Back</button>
        <!-- <Link class="btn-primary w-1/2" :href="route('children.get')">Back</Link> -->
    </div>
    
    
</template>

<script setup>
import {Link} from "@inertiajs/vue3"; 
const goBack = () => window.history.back()
defineProps({
    code: Object,
})
</script>