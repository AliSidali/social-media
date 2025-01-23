<template>
    <div class="flex items-center  cursor-pointer px-3 py-2  hover:bg-gray-100">
        <img class="w-[32px] h-[32px] rounded-full " :src="image??'/storage/defaults/avatar.png'" alt="">
        <div class="ml-3">
            <h4 class="font-black">{{ name }} </h4>
        </div>
    </div>
</template>
<script setup>
defineProps({
    image: String,
    name: String
})
</script>
