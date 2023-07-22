<template>
    <div class="input-error">
        {{ error }}
    </div>
</template>

<script setup>
    defineProps({
        error: String
    })
</script>