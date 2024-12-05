<template>
    <Link :href="route('group.profile', group.slug)" class="flex mb-3  px-1 py-2 hover:bg-gray-100">
        <img class="w-[35px] h-[35px] rounded-full " :src="group.thumbnail_path" alt="">
        <div class="ml-1 w-full">
            <div class="flex justify-between">
                <h4 class="font-black text-m">{{ group.name }} </h4>
                <h2 class="text-indigo-700 text-sm">{{ group.status==="approved" ? (group.role == "admin" ? group.role : "") : 'Not Approved' }}</h2>
            </div>
            <p class="text-xs text-gray-500">{{ group.description }} </p>
        </div>
    </Link>
</template>
<script setup>

import { Link } from '@inertiajs/vue3';
defineProps({
    group: Object
}

)
</script>
