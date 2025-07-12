<template>
    <div class="flex items-center gap-2 mb-3 ">
        <a href="#" class="border-2 rounded-full hover:border-blue-500  transition-all">
            <img class="w-[40px] h-[40px] rounded-full" :src="post.user.avatar_path" alt="">
        </a>
        <div class="ml-3">
            <h4 class="font-bold flex items-center">
            <Link :href="route('profile.index', post.user.username)" class="hover:underline transition-all"> {{ post.user.name }}</Link>
            <template v-if="post.group">
                <ChevronRightIcon class="w-4" />
                <Link  :href="route('group.profile', post.group.slug)" class="hover:underline transition-all">{{ post.group.name }}</Link>
            </template>
            </h4>
            <small v-if="showTime" class="text-gray-400">{{ post.created_at }}</small>

        </div>
    </div>
</template>

<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

defineProps({
    post: {
        type:Object,
        required: true
    },
    showTime:{
        type:Boolean,
        default: true
    }

})
</script>
