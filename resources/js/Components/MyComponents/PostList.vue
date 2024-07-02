<script setup>
import { ref } from 'vue';
import PostItem from './PostItem.vue'
import PostModal from './PostModal.vue';
import { usePage } from '@inertiajs/vue3';
import PreviewAttachmentModal from './PreviewAttachmentModal.vue';



defineProps({
    posts: Array,
})
const user = usePage().props.auth.user;

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

//FOR PREVIEW ATTACHMENT MODAL
const isOpenAttachment = ref(false);
const previewAttachmentsPost = ref({
    post: {}
});
const previewAttachmentModal = (post, index)=>{
    previewAttachmentsPost.value = {
        post,
        index
    };
    isOpenAttachment.value = true;
}

</script>

<template>
    <div class="lg:overflow-auto lg:flex-1 ">
        <PostItem @onAttachmentClick="previewAttachmentModal" @editClick="openEditModal" v-for="(post, index) in posts" :key="index" :post="post" />
        <PostModal v-model="showEditModal" :post="editPost" @hideModal="onModalHide"/>
        <PreviewAttachmentModal :attachments="previewAttachmentsPost.post.attachments" :index="previewAttachmentsPost.index" v-model="isOpenAttachment" />

    </div>

</template>

