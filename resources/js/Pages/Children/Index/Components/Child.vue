<template>
    <Box>        
        <div>
            <Link>
                
                <div class="flex items-center justify-between gap-1">
                    <div class="text-md  text-gray-500">
                        {{ child.name }}
                    </div>
                    <div class="text-md text-gray-500">
                        Coins: {{ child.coins }}
                    </div>
                </div>
                <div class="flex items-center justify-end mt-2">
                    <Link class="btn-outline" method="delete" as="button" :href="route('children.detouch', { child: child.id})">Detach child</Link>
                </div>
                
            </Link>
        </div>
    </Box>
</template>

<script setup>
    import {Link} from "@inertiajs/vue3"
    import Box from "@/Components/Ui/Box.vue";

    const props = defineProps({
            child: Object,
        })
</script>