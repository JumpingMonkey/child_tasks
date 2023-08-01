<template>
    <div v-if="parent">
        <div v-if="parent.length" class="flex items-center justify-end">
            <Link class="btn-primary" :href="route('child.parent.create')">Add parent</Link>
        </div>
        <Parent class="mt-2" :parent="parent"></Parent>
    </div>
    <div v-else>
        <div  class="flex items-center justify-end">
            <Link class="btn-primary" :href="route('child.parent.create')">Add parent</Link>
        </div>
    </div>
    
</template>

<script setup>
import {Link} from '@inertiajs/vue3'
import Parent from "@/Pages/Parents/Index/Components/Parent.vue"

const prop = defineProps({
    parent: Object
})
</script>