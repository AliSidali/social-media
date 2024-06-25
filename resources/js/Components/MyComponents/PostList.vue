<script setup>
import { ref } from 'vue';
import PostItem from './PostItem.vue'
import PostModal from './PostModal.vue';
import { usePage } from '@inertiajs/vue3';


defineProps({
    posts: Array,
})
const user = usePage().props.auth.user;
//const authUser = page.auth.user

const showEditModal = ref(false);
const editPost = ref({});
const openEditModal= (newpost)=>{
    showEditModal.value= true;
    editPost.value = newpost;
}

const onModalHide = ()=>{
    editPost.value = {
        body: '',
        user: user
    }
}
</script>

<template>
    <div class="lg:overflow-auto lg:flex-1 ">
        <PostItem @editClick="openEditModal" v-for="(post, index) in posts" :key="index" :post="post" />
        <PostModal v-model="showEditModal" :post="editPost" @hideModal="onModalHide"/>
    </div>

</template>

