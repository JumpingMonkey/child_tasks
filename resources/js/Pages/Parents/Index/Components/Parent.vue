<template>
    <Box>        
        <div>
            <Link>
                <div class="flex items-center justify-between gap-1">
                    <div class="text-md  text-gray-500">
                        {{ parent.name }}
                    </div>
                </div>    
            </Link>
        </div>
    </Box>
</template>

<script setup>
    import {Link} from "@inertiajs/vue3"
    import Box from "@/Components/Ui/Box.vue";

    const props = defineProps({
            parent: Object,
        })
</script>