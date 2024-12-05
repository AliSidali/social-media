<template>
    <AuthenticatedLayout>
        <div class="text-center py-28">
            <h1 class="text-[40px]">
                {{ text }}
            </h1>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props  = defineProps({
    'text': String
});
</script>
