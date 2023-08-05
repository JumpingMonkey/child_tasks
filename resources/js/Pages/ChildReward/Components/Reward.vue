<template>
    <Box>
        <div class="flex sm:flex-row flex-col gap-2">
            <div class="basis-1/4 bg-gray-200 rounded-md flex items-center justify-center">
                <div v-if="reward?.images?.length">
                    <img :src="reward.images[0].src"  class="">
                </div>
                <div v-else class="text-gray-500">
                    No image
                </div>                
            </div>
            <div class="basis-3/4">
                <div class="flex items-center space-x-4 justify-between">
            <Link class="font-medium text-blue-600" :href="route('child.rewards.show', {reward: reward.id})">{{ reward.title }}</Link>
            <div class="status-label" :class="{'border-green-500 border-2': active, 'border-red-500 border-2': !active}" >{{ active? 'Active': 'Inactive' }}</div>
        </div>
        <div class="flex items-center justify-between space-y-4">
            <div class="">Price: {{ reward.price }}</div>
            <div class="">Creator: {{ reward.user.name }}</div>  
        </div>
        <div class="flex items-center justify-end space-x-4 mt-4">
            <div v-if="reward.status">
                <Link v-if="!reward.claimed_by" method="put" as="button" class="btn-outline border-blue-500 hover:bg-blue-700 hover:text-white duration-100" :href="route('child.rewards.claim', {reward: reward.id})">
                Claim
                </Link>
            </div>
            <Link v-if="reward.user.id == user.id" class="btn-outline" :href="route('child.reward.image.create', {reward: reward.id})">Images({{ reward.images.length }})</Link>
            <Link class="btn-outline" :href="route('child.rewards.show', {reward: reward.id})">Preview</Link>
            <Link v-if="reward.user.id == user.id" class="btn-outline" :href="route('child.rewards.edit', {reward: reward.id})">Edit</Link>
            <Link v-if="reward.user.id == user.id" class="btn-outline-delete" :href="route('child.rewards.destroy', {reward: reward.id})" 
                method="delete" as="button">Delete</Link>
        </div>
        
            </div>
        </div>
        
    </Box>
</template>

<script setup>
import Box from '@/Components/Ui/Box.vue';
import {Link} from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { computed } from 'vue';

const user = computed(
    () => usePage().props.user,
)

const props = defineProps({
    reward: Object,
})

const active = props.reward.status

</script>