<template>
  <div class="flex items-center justify-end">
    <Link class="btn-primary" :href="route('parent.tasks.create')">Add task</Link>
  </div>
  <Task v-for="task in tasks" :key="task.id" :task="task" class="mt-2"></Task>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import Task from "@/Pages/ParentsTasks/Components/Task.vue";
defineProps({
    tasks: Object,
})
</script>