<template>
        <AuthenticatedLayout>
            <div class="w-[600px] mx-auto h-full">
                <PostItem class="overflow-auto h-full" :post="post"  @onShowComments="showCommentModal=true" @onAttachmentClick="showAttachmentModal"/>
                <PostCommentModal :post="post" v-model="showCommentModal"/>
                <PreviewAttachmentModal :attachments="post.attachments" :index="currentIndex" v-model="isAttachmentModalShown"  />
            </div>
        </AuthenticatedLayout>
</template>
<script setup>
import PostCommentModal from '@/Components/MyComponents/PostCommentModal.vue';
import PostItem from '@/Components/MyComponents/PostItem.vue';
import PreviewAttachmentModal from '@/Components/MyComponents/PreviewAttachmentModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
const props = defineProps({
    post:Object
})

const showCommentModal = ref(false);

const isAttachmentModalShown = ref(false);
const attachments = ref([]);
const currentIndex = ref(null);
const showAttachmentModal = (attachments, index)=>{

    isAttachmentModalShown.value = true;
    attachments.value = attachments;
    currentIndex.value = index;
};






</script>
