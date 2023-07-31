<template>
    <form @submit.prevent="create">
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
          <label class="label">Price</label>
          <input type="text" v-model.number="form.price" class="input"/>
          <FormErrorMessage v-if="form.errors.price" :error="form.errors.price" />
        </div>

        <div class="col-span-6">
          <button type="submit" class="btn-primary">
            Create
          </button>
        </div>
      </div>
    </form>
  </template>

<script setup>
    import {useForm, Link} from '@inertiajs/vue3';
    import FormErrorMessage from "@/Components/FormErrorMessage.vue";
    defineProps({
        children: Object
    })
    const form = useForm({
        title: null,
        description: null,
        price: 1,
    })

    const create = () => form.post(route('parent.rewards.store'), form)

</script>
