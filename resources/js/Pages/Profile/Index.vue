<template>
    <UserProfileLayout :errors="errors" :success="success" :user="user" :followers="followers" :isCurrentUserFollower="isCurrentUserFollower">
        <TabPanels class="mt-2">
            <TabPanel  class="bg-white p-3 shadow">
                <!-- for pinned post -->
                <PostList :posts="posts" />
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow">
                followers page
                <div class="grid grid-cols-2 gap-2">
                    <UserListItem v-for="(user, index) in followers" :key="index" :user="user" :href="route('profile.index', user.username)" class="bg-gray-100 shadow" />
                </div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow">
                followings page
                <div class="grid grid-cols-2 gap-2">
                    <UserListItem v-for="(user, index) in followings" :key="index" :user="user" :href="route('profile.index', user.username)"  class="bg-gray-100 shadow"/>
                </div>
            </TabPanel>
            <TabPanel class="bg-white p-3 shadow">
                <PhotosTab :attachments="attachments"  />
            </TabPanel>

            <TabPanel v-if="isUserProfile">
                <Edit :mustVerifyEmail="mustVerifyEmail" :status="status" />
            </TabPanel>
        </TabPanels>
    </UserProfileLayout>

</template>


<script setup>
import Edit from "./Edit.vue";
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import PostList from "@/Components/MyComponents/PostList.vue";
import PostItem from "@/Components/MyComponents/PostItem.vue";
import UserListItem from "@/Components/MyComponents/UserListItem.vue";
import PhotosTab from "@/Components/MyComponents/PhotosTab.vue";
import {  TabPanels, TabPanel } from '@headlessui/vue'
import { usePage } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";


const props = defineProps({
    errors: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    success: {
        type: String,
    },
    user: {
        type: Object
    },
    followers: Object,
    followings: Object,
    isCurrentUserFollower: Boolean,
    posts: {
        type:Object
    },
    attachments:Array,
})

const page = usePage();
const authUser  = page.props.auth.user;
const translations = page.props.translations;
const isPhotoModalOpen=ref(false);

const isUserProfile = computed(()=>{
    return authUser && authUser.id == props.user.id;
});



// const loadingSpinner = ref(false);

// const loadMorePosts = ()=>{
//     if(postsData.value.nextLink){
//         loadingSpinner.value = true;
//         setTimeout(()=>{
//             axiosClient.get(postsData.value.nextLink).then(({data})=>{
//                     postsData.value.posts = [...postsData.value.posts, ...data.data];
//                     postsData.value.nextLink = data.links.next;
//                     loadingSpinner.value = false;
//             })
//         }, 3000)
//     }
// }

const instantPinPost = (isPinned)=>{
    if(props.pinnedPost){
        props.pinnedPost.isPinned=isPinned;
    }
}

</script>


