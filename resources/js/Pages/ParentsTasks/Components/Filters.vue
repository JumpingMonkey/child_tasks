<template>
    <form @submit.prevent="filter">
        <div class="mb-8 mt-4 flex flex-wrap gap-2">
            <div class="flex flex-nowrap items-center">
                <label class="label">Status</label>
                <select v-model="filterForm.status" class="input">
                    <option v-for="status in props.statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                </select>
            </div>
            <button type="submit" class="btn-normal">Filter</button>
            <button type="reset" @click="clear">Clear</button>
        </div>
    </form>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        filters: Object,
        statuses: Object
    })

    const filterForm = useForm({
        status: props.filters.status ?? null,
      
    })
    const filter = () => {
        filterForm.get(
            route('parent.tasks.index'),
            {
                preserveState: true,
                preserveScroll: true,
            }
        )
    }
    const clear = () => {
        filterForm.status = null
        filter()
    }
</script>
