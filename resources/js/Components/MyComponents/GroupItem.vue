<template>
    <Link :href="route('group.profile', group.slug)" class="flex mb-3  px-1 py-2 hover:bg-gray-100 dark:hover:bg-slate-900">
        <img class="w-[35px] h-[35px] rounded-full " :src="group.thumbnail_path" alt="">
        <div class="ml-1 w-full">
            <div class="flex justify-between hover:underline">
                <h4 class="font-black text-m">{{ group.name }} </h4>
            </div>
            <span class="text-indigo-700 text-xs">{{ group.status==="approved" ? (group.role == "admin" ? group.role : "") : 'Not Approved' }}</span>

            <!-- <p class="text-xs text-gray-500">{{ group.description }} </p> -->
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
