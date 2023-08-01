<template>
        <Link :href="route('child.tasks.index')" class="">
            ← Go back to tasks
        </Link>
    <form @submit.prevent="update">
      <div class="grid grid-cols-6 gap-4">
        <div class="col-span-2">
          <label class="label">Status</label>
          <select v-model="form.task_status_id" class="input">
            <option v-for="status in prop.statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
          </select>
          <FormErrorMessage v-if="form.errors.executor_id" :error="form.errors.executor_id" />
        </div>
        <div class="col-span-6">
          <button type="submit" class="btn-primary">
            Update
          </button>
        </div>
        
      </div>
      
    </form>
  </template>

<script setup>
    import {useForm, Link} from '@inertiajs/vue3';
    import FormErrorMessage from "@/Components/FormErrorMessage.vue";
    const prop = defineProps({
        task: Object,
        statuses: Object
    })
    const form = useForm({
        task_status_id: prop.task.status.id,
    })

    const update = () => form.put(route('child.tasks.update', {task: prop.task.id}))

</script>
