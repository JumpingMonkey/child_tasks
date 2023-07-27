<template>
    <Box>
        <div class="flex sm:flex-row flex-col gap-2">
            <div class="basis-1/4 bg-gray-200 rounded-md">
                <img src="#" class="">
            </div>
            <div class="basis-3/4">
                <div class="flex items-center space-x-4 justify-between">
            <Link class="font-medium text-blue-600" :href="route('parents-rewards.show', {parents_reward: reward.id})">{{ reward.title }}</Link>
            <div class="status-label" :class="{'border-green-500 border-2': active, 'border-red-500 border-2': !active}" >{{ active? 'Active': 'Inactive' }}</div>
        </div>
        <div class="flex items-center justify-between space-y-4">
            <div class="">Price: {{ reward.price }}</div>
            <div class="">Creator: {{ reward.user.name }}</div>  
        </div>
        
        <div class="flex items-center justify-end space-x-4 mt-4">
            <Link class="btn-outline" :href="route('parents-rewards.show', {parents_reward: reward.id})">Preview</Link>
            <Link class="btn-outline" :href="route('parents-rewards.edit', {parents_reward: reward.id})">Edit</Link>
            <Link class="btn-outline-delete" :href="route('parents-rewards.destroy', {parents_reward: reward.id})" 
                method="delete" as="button">Delete</Link>
        </div>
            </div>
        </div>
        
    </Box>
</template>

<script setup>
import Box from '@/Components/Ui/Box.vue';
import {Link} from "@inertiajs/vue3";
const props = defineProps({
    reward: Object,
})

const active = props.reward.status

</script>