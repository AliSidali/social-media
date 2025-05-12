<template>
    <div class="flex items-center  cursor-pointer px-3 py-2  hover:bg-gray-100">
        <Link :href="href ?? '#'">
            <img class="w-[32px] h-[32px] rounded-full " :src="user.avatar??'https://picsum.photos/200'" alt="">
        </Link>
        <div class="ml-3">
        <h4 class="font-black">{{ user.name }}</h4>
        </div>
    </div>
</template>
<script setup>
import {Link} from '@inertiajs/vue3'
defineProps({
    user:Object,
    href:String
})
</script>
