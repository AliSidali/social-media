<template>
<a :href="url" target="_blank" v-if="preview" class="border border-indigo-300">
    <img :src="preview.image" class="max-w-full max-h-80 mx-auto" alt="">
    <div class="p-3 bg-indigo-100 mt-1">
        <h3 class="font-semibold">{{ preview.title }}</h3>
        <p class="text-sm text-gray-600">{{preview.description }}</p>
    </div>
</a>
</template>
<script setup>
const props = defineProps({
    'preview':Object,
    'url':String
});
</script>
