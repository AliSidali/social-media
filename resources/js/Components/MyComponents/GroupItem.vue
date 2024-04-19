<template>
    <div class="flex mb-3 cursor-pointer px-3 py-2 hover:bg-gray-100">
        <img class="w-[48px] h-[48px] rounded-full " :src="image" alt="">
        <div class="ml-3">
            <h4 class="font-black text-lg">{{ title }} </h4>
            <p class="text-xs text-gray-500">{{ content }} </p>
        </div>
    </div>
</template>
<script setup>
defineProps({
    image: String,
    title: String,
    content: String
})
</script>
