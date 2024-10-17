<script setup>
import { ref } from 'vue';
import PostItem from './PostItem.vue'
import PostModal from './PostModal.vue';
import { usePage } from '@inertiajs/vue3';
import PreviewAttachmentModal from './PreviewAttachmentModal.vue';
import PostCommentModal from './PostCommentModal.vue';



defineProps({
    posts: Array,
})
const user = usePage().props.auth.user;

//previewing post create and edit modal
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
const previewAttachmentModal = (attachments, index)=>{
    console.log(attachments);

    previewAttachmentsPost.value = {
        attachments,
        index
    };
    isOpenAttachment.value = true;
}

//for comments Modal
const isCommentModalOpen = ref(false);
const commentPost = ref({});
const showComments = (post)=>{
    commentPost.value = post;
    isCommentModalOpen.value = true;
}

//hivalue

</script>

<template>
    <div class="lg:overflow-auto lg:flex-1 ">
        <PostItem  @onShowComments="showComments" @onAttachmentClick="previewAttachmentModal" @editClick="openEditModal" v-for="(post, index) in posts" :key="index" :post="post" /> -->
        <PostModal v-model="showEditModal" :post="editPost" @hideModal="onModalHide"/>
        <PreviewAttachmentModal :attachments="previewAttachmentsPost.attachments" :index="previewAttachmentsPost.index" v-model="isOpenAttachment" />
        <PostCommentModal :post="commentPost" v-model="isCommentModalOpen" @onAttachmentClick="previewAttachmentModal"/>
    </div>

</template>

