<template>
    <div class="flex items-center justify-end">
      <Link class="btn-primary" :href="route('parent.rewards.create')">Add reward</Link>
    </div>
    <Reward v-for="reward in rewards" :key="reward.id" :reward="reward" class="mt-2"></Reward>
</template>

<script setup>
  import {Link} from "@inertiajs/vue3";
  import Reward from "@/Pages/Rewards/Components/Reward.vue";
  defineProps({
      rewards: Object,
  })
</script>