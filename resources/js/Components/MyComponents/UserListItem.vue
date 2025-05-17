<template>
    <div class="group flex items-center  cursor-pointer px-3 py-2  hover:bg-gray-100">
        <Link :href="href ?? '#'" class="group flex gap-2">
            <img class="w-[32px] h-[32px] rounded-full " :src="user.avatar_path??'https://picsum.photos/200'" alt="">
            <span class="font-black group-hover:underline ">{{ user.name }}</span>
        </Link>

    </div>
</template>
<script setup>
import {Link} from '@inertiajs/vue3'
defineProps({
    user:Object,
    href:String
})
</script>
