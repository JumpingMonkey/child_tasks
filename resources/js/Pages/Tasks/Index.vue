<template>
  <Task v-for="task in tasks" :key="task.id" :task="task" class="mt-2"></Task>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import Task from "@/Pages/Tasks/Components/Task.vue";
defineProps({
    tasks: Object,
})
</script>