<script setup>
import GroupList from '@/Components/MyComponents/GroupList.vue';
import FollowingList from '@/Components/MyComponents/FollowingList.vue';
import CreatePost from '@/Components/MyComponents/CreatePost.vue';
import PostList from '@/Components/MyComponents/PostList.vue';
import { Head, Link } from '@inertiajs/vue3';




</script>

<template>
    <Head title="Social Media Website" />
    <div class="grid gap-2 px-4 py-4 lg:grid-cols-12 lg:py-0">
        <div class="lg:h-screen lg:col-span-3 lg:order-1 lg:py-4">
            <GroupList />
        </div>
        <div class="lg:col-span-3 lg:order-3 lg:py-4 lg:h-screen ">
            <FollowingList />
        </div>
        <div class="h-screen flex flex-col lg:col-span-6 lg:order-2 lg:py-4">
            <CreatePost />
            <PostList />
        </div>


    </div>
</template>
