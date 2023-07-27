<template>
    <form @submit.prevent="update">
      <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
          <label class="label">
            Title</label>
          <input type="text" v-model="form.title"
            class="input"/>
          <FormErrorMessage v-if="form.errors.title" :error="form.errors.title" />
        </div>

        <div class="col-span-6">
          <label class="label">Description</label>
          <textarea type="text" v-model="form.description" class="input"> </textarea> 
          <FormErrorMessage v-if="form.errors.description" :error="form.errors.description" />
        </div>

        <div class="col-span-2">
          <label class="label">Coins</label>
          <input type="text" v-model.number="form.coins" class="input"/>
          <FormErrorMessage v-if="form.errors.coins" :error="form.errors.coins" />
        </div>

        <div class="col-span-4">
          <label class="label">End date</label>
          <input type="datetime-local" v-model="form.planned_and_date" class="input"/>
          <FormErrorMessage v-if="form.errors.planned_and_date" :error="form.errors.planned_and_date" />
        </div>

        <div class="col-span-6">
          <label class="label">Child</label>
          <select v-model="form.executor_id" class="input">
            <option v-for="child in prop.task.creator.children" :key="child.id" :value="child.id">{{ child.name }}</option>
          </select>
          <Link class="text-sm text-blue-700 hover:text-blue-500" :href="route('children.attach')">Add child</Link>
          <FormErrorMessage v-if="form.errors.executor_id" :error="form.errors.executor_id" />
        </div>

        <div class="col-span-2">
          <label class="label">Image required</label>
          <input type="checkbox" v-model="form.is_image_required" class=""/>
          <FormErrorMessage v-if="form.errors.is_image_required" :error="form.errors.is_image_required" />
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
        task: Object
    })
    const form = useForm({
        title: prop.task.title,
        description: prop.task.description,
        coins: prop.task.coins,
        planned_and_date: prop.task.planned_and_date,
        executor_id: prop.task.executor.id,
        is_image_required: prop.task.is_image_required,
    })

    const update = () => form.put(route('parents-tasks.update', {parents_task: prop.task.id}))

</script>
