<script setup>
import GroupList from '@/Components/MyComponents/GroupList.vue';
import FollowingList from '@/Components/MyComponents/FollowingList.vue';
import CreatePost from '@/Components/MyComponents/CreatePost.vue';
import PostList from '@/Components/MyComponents/PostList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    success:  String,
    posts:  Object,
    groups: Array,
    followings:Array

})



</script>

<template>

    <Head title="Social Media Website" />

    <AuthenticatedLayout>

        <!-- "auto-rows-fr" important class for specifying grid child height  -->
        <div class="grid  gap-2 px-4 pt-8 lg:grid-cols-12  lg:h-full lg:auto-rows-fr">
            <div class=" lg:col-span-3 lg:order-1 ">
                <GroupList  :groups="groups"/>
            </div>
            <div class="lg:col-span-3 lg:order-3   ">
                <FollowingList :followings="followings"/>
            </div>
            <div class="flex flex-col lg:col-span-6 lg:order-2 ">

                <CreatePost :success="success"/>
                <PostList :posts="posts" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
