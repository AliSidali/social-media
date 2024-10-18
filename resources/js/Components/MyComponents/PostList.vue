<script setup>
import { onMounted, ref } from 'vue';
import PostItem from './PostItem.vue'
import PostModal from './PostModal.vue';
import { usePage } from '@inertiajs/vue3';
import PreviewAttachmentModal from './PreviewAttachmentModal.vue';
import PostCommentModal from './PostCommentModal.vue';
import axiosClient from '@/axiosClient'


const props = defineProps({
    posts: Array,
})
const page = usePage();
const user = page.props.auth.user;

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


// FOR LOADING MORE POSTS
const loadMoreIntersect = ref(null);
const allPosts = ref({
    data:page.props.posts.data,
    next:page.props.posts.links.next
})
const loadMore = ()=>{
    axiosClient.get(allPosts.value.next).then(({data})=>{
        allPosts.value.data = [...allPosts.value.data, ...data.data];
        allPosts.value.next = data.links.next;

    })
}

onMounted(()=>{
    const observer = new IntersectionObserver(
    (entities)=>entities.forEach((entity)=>{
        if(entity.isIntersecting && allPosts.value.next){
            loadMore();
        }

    })
)
observer.observe(loadMoreIntersect.value);
})




</script>

<template>
    <div class="lg:overflow-auto lg:flex-1 ">
        <PostItem  @onShowComments="showComments" @onAttachmentClick="previewAttachmentModal" @editClick="openEditModal" v-for="(post, index) in allPosts.data" :key="index" :post="post" />
        <div ref="loadMoreIntersect" v-if="allPosts.next">load more...</div>
        <div v-else>no more posts</div>
        <PostModal v-model="showEditModal" :post="editPost" @hideModal="onModalHide"/>
        <PreviewAttachmentModal :attachments="previewAttachmentsPost.attachments" :index="previewAttachmentsPost.index" v-model="isOpenAttachment" />
        <PostCommentModal :post="commentPost" v-model="isCommentModalOpen" @onAttachmentClick="previewAttachmentModal"/>
    </div>

</template>

