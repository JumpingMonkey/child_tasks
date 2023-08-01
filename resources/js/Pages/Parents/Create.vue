<template>
    <form @submit.prevent="create">
      <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6">
          <label class="label">
            Parent Code</label>
          <input type="text" v-model="form.code"
            class="input"/>
          <FormErrorMessage v-if="form.errors.code" :error="form.errors.code" />
        </div>

        <div class="col-span-6">
          <button type="submit" class="btn-primary">
            Attach
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
        code: null,
    })

    const create = () => form.post(route('child.parent.store'), form)

</script>
