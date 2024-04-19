<template>
    <div class="flex items-center mb-3 cursor-pointer px-3 py-2 hover:bg-gray-100">
        <img class="w-[32px] h-[32px] rounded-full " :src="image" alt="">
        <div class="ml-3">
            <h4 class="font-black">{{ title }} </h4>
        </div>
    </div>
</template>
<script setup>
defineProps({
    image: String,
    title: String
})
</script>
