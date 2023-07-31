<template>
    <Box>
        <div class="flex items-center space-x-4 justify-between">
            <Link class="font-medium text-blue-600" :href="route('parent.tasks.show', {task: task.id})">{{ task.title }}</Link>
            <div class="status-label" >{{ task.status.name }}</div>
        </div>
        <div class="flex items-center justify-between space-y-4">
            <div class="">Award: {{ task.coins }}</div>
            <div class="">End date: {{ task.planned_and_date }}</div>  
        </div>
        <div class="flex items-center justify-start">
            <div>Child: {{ task.executor.name }}</div>
        </div>
        <div class="flex items-center justify-end space-x-4">
            <Link class="btn-outline" :href="route('parent.tasks.show', {task: task.id})">Preview</Link>
            <Link class="btn-outline" :href="route('parent.tasks.edit', {task: task.id})">Edit</Link>
            <Link class="btn-outline-delete" :href="route('parent.tasks.destroy', {task: task.id})" 
                method="delete" as="button">Delete</Link>
        </div>
    </Box>
</template>

<script setup>
import Box from '@/Components/Ui/Box.vue';
import {Link} from "@inertiajs/vue3";
defineProps({
    task: Object,
})

</script>