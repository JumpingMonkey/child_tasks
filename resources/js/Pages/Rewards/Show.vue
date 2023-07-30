<template>
    <Box class="flex flex-col space-y-4 ">
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4 font-medium text-gray-600">Title:</div>
            <div class="w-3/4">{{ reward.title }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Description:</div>
            <div class="w-3/4">{{ reward.description }}</div>
        </div>
        <div class="flex items-center justify-start space-x-4 ">
            <div class="w-1/4">Status:</div>
            <div class="w-3/4">{{ reward.status ? "Active" : 'Inactive' }}</div>
        </div>
        <div class="flex items-center justify-end space-x-4">
            <Link class="btn-outline" :href="route('reward.image.create', {reward: reward.id})">Add image</Link>
            <Link class="btn-outline" :href="route('parents-rewards.edit', {parents_reward: reward.id})">Edit</Link>
            <Link class="btn-outline-delete" :href="route('parents-rewards.destroy', {parents_reward: reward.id})" 
                method="delete" as="button">Delete</Link>
        </div>
    </Box>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import Box from "@/Components/Ui/Box.vue";
defineProps({
    reward: Object
})
</script>