<template>
    <Link :href="route('child.tasks.index')">
            ← Go back to tasks
        </Link>
    <Box class="flex flex-col space-y-4 mt-2">
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4 font-medium text-gray-600">Title:</div>
            <div class="w-3/4">{{ task.title }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Description:</div>
            <div class="w-3/4">{{ task.description }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Finish date:</div>
            <div class="w-3/4">{{ task.planned_and_date }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Is image require?</div>
            <div class="w-3/4">{{ task.is_image_required }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Awords:</div>
            <div class="w-3/4">{{ task.coins }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Status:</div>
            <div class="w-3/4">{{ task.status.name }}</div>
        </div>
       
        <div class="flex items-center justify-end space-x-4">
            <Link class="btn-outline" :href="route('child.tasks.edit', {task: task.id})">Edit</Link>
            <!-- <Link class="btn-outline-delete" method="delete" as="button">Delete</Link> -->
        </div>
    </Box>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import Box from "@/Components/Ui/Box.vue";
defineProps({
    task: Object
})
</script>