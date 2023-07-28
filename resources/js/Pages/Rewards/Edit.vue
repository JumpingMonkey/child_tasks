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
          <label class="label">Price</label>
          <input type="text" v-model.number="form.price" class="input"/>
          <FormErrorMessage v-if="form.errors.price" :error="form.errors.price" />
        </div>

        <div class="col-span-6">
          <label class="label">Status</label>
          <select v-model="form.status" class="input">
            <option v-for="option in options" :key="option.value" :value="option.value" >{{ option.text }}</option>
            <!-- <option value="false">Inactive</option> -->
          </select>
          <FormErrorMessage v-if="form.errors.status" :error="form.errors.status" />
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
    import {useForm, Link,} from '@inertiajs/vue3';
    import FormErrorMessage from "@/Components/FormErrorMessage.vue";
    import {ref} from "vue";

    const prop = defineProps({
        reward: Object
    })

    const options = ref([
  { text: 'Active', value: 1 },
  { text: 'Inactive', value: 0 },
])

    const form = useForm({
        title: prop.reward.title,
        description: prop.reward.description,
        price: prop.reward.price,
        status: prop.reward.status,
    })

    const update = () => form.put(route('parents-rewards.update', {parents_reward: prop.reward.id}))

</script>
