<script setup>
import FollowingList from '@/Components/MyComponents/FollowingList.vue';
import CreatePost from '@/Components/MyComponents/CreatePost.vue';
import PostList from '@/Components/MyComponents/PostList.vue';
import GroupList from '@/Components/MyComponents/GroupList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

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

        <div class="px-4 pt-4">


            <!-- "auto-rows-fr" important class for specifying grid child height  -->
            <div class="grid  gap-2 lg:grid-cols-12  lg:h-full lg:auto-rows-fr">
                <div class="lg:col-span-3 lg:order-1 lg:max-h-screen">
                    <GroupList  :groups="groups"/>
                </div>
                <div class="lg:col-span-3 lg:order-3 lg:max-h-screen  ">
                    <FollowingList :followings="followings"/>
                </div>
                <div class="flex flex-col lg:col-span-6 lg:order-2 ">

                    <CreatePost :success="success"/>
                    <PostList :posts="posts" />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
