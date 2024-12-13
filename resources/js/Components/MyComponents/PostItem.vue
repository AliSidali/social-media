<template>
    <div class="bg-white py-3 px-5 border rounded mb-3">
        <!-- POST HEAD -->
        <div class="flex justify-between">
            <PostUserHeader  :post="post"/>
            <!-- the three dots section -->
            <EditDeleteDropdown @edit="openEditModal" />
        </div>


        <!-- POST DESCRIPTION -->
        <div class="mb-3">
            <ReadMoreLess :content="post.body" />
        </div>
        <!-- POST ATTACHEMENTS -->
        <div class="grid  gap-2 mb-3" :class="post.attachments.length ==1?'grid-cols-1 ':'grid-cols-2'">
            <div v-for="(attachment, index) in post.attachments.slice(0, 4)" :key="index" >
                <div @click="previewAttachment(post, index)" class="relative group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 cursor-pointer">
                    <div v-if="post.attachments.length > 4 && index == 3"  class="absolute inset-0 bg-black/60 text-white flex justify-center items-center text-2xl">
                        + {{ post.attachments.length-4 }} more
                    </div>
                    <img v-if="isImage(attachment)"    :src="attachment.url" class="object-contain " alt="">
                    <div v-else class="flex flex-col justify-center items-center">
                        <PaperClipIcon class=" w-10 h-10 mb-3" />
                        <small>{{ attachment.name }}</small>
                    </div>
                    <a @click.stop :href="route('attachment.download', attachment)" class="p-2 text-white bg-gray-800 rounded absolute top-2 right-2 opacity-0  hover:bg-gray-600 group-hover:opacity-100">
                        <ArrowDownTrayIcon class="w-4"/>
                    </a>
                </div>

            </div>

        </div>


        <!-- LIKE AND COMMENT -->
        <!-- info about comments and reactions -->
        <div class="flex items-center justify-between border-b mb-2 px-4 py-2 ">
            <div class="flex gap-1">
                <HandThumbUpIcon v-if="hasLikeReaction" class="w-6 text-white rounded-full bg-blue-600 p-1"/>
                <HeartIcon v-if="hasLoveReaction" class="w-6 text-white rounded-full bg-red-600 p-1"/>
                <span class="ml-2">{{ post.reactions.length }}</span>
            </div>
            <div>
                <span>{{ post.post_comments_num }} comments</span>
            </div>

        </div>

        <!-- like and comment buttons -->
        <div class=" flex mb-3 gap-2">
            <div class="flex-1 relative"  @mouseover="showReaction"  @mouseleave="hideReaction" @click="hideReaction">
                <div  v-if="isShowReaction" class="absolute flex -top-14 gap-1 border rounded shadow-sm bg-white w-full py-3 justify-center">
                    <button  @click="sendPostReaction('like')"> <HandThumbUpIcon class="w-9 text-white rounded-full bg-blue-600 p-2"/></button>
                    <button  @click="sendPostReaction('love')"><HeartIcon  class="w-9 text-white rounded-full bg-red-600 p-2"/> </button>
                </div>
                <button
                    @click="sendPostReaction('like')"
                    class="flex w-full items-center justify-center gap-2 text-gray-800  py-2 rounded-lg "
                    :class="post.has_current_user_reaction ? 'bg-sky-100 hover:bg-sky-200' : 'bg-gray-100 hover:bg-gray-200'"
                >
                    <HandThumbUpIcon class="w-6"/>
                    <span>Like</span>
                </button>
            </div>

            <button @click="showComments" class="flex flex-1 items-center justify-center gap-2 bg-gray-100 py-2  rounded-lg hover:bg-gray-200">
                <ChatBubbleLeftRightIcon class="w-6" />
                <span>Comment</span>
            </button>
        </div>


    </div>

</template>
<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import { ArrowDownTrayIcon, HandThumbUpIcon, ChatBubbleLeftRightIcon, PencilIcon, TrashIcon, PaperClipIcon, HeartIcon, FaceSmileIcon, FaceFrownIcon } from '@heroicons/vue/24/solid';
import { MenuItem } from '@headlessui/vue'
import PostUserHeader from './PostUserHeader.vue';
import ReadMoreLess from './ReadMoreLess.vue';
import EditDeleteDropdown from './EditDeleteDropdown.vue';
import {helpers} from '@/helpers';
import axiosClient from '@/axiosClient';


import { onMounted, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';


const {isImage} = helpers();

const props = defineProps({
    post: Object,
})





//SHOWING EDIT POST MODAL
const emit = defineEmits(['editClick', 'onAttachmentClick', 'onShowComments', 'postValue']);
const openEditModal = ()=>{

    emit('editClick', props.post);
};


//DELETE POST

const deletePost = ()=>{
    if(window.confirm('Are you sure you want to delete this post?')){
        router.delete(route('post.destroy', props.post.id), {
            preserveScroll:true
        });
    }
}




//ATTACHMENT MODAL

const previewAttachment = (post, index)=>{
    emit('onAttachmentClick', post.attachments, index);
}

//SEND REACTION
const sendPostReaction = (reaction)=>{
    axiosClient.post(route('post.reaction', props.post.id), {
        reaction: reaction
    }).then(({data})=>{
        // showing number of reactions and wether the current user reacted to the post without refresh the page

        props.post.reactions = data.reactions;
        props.post.has_current_user_reaction = data.has_reaction

        // show   reactions type of the current post without refresh
        showReactionIcons();

    });
}

//SHOW AND HIDE REACTION BUTTONS
const isShowReaction = ref(false);

const showReaction = ()=>{
    setTimeout(()=>{
        isShowReaction.value= true
    }, 300)
}

const hideReaction = ()=>{
    setTimeout(()=>{
        isShowReaction.value= false
    }, 300)
}

// show all reactions type  of the current post in the first laod
const hasLikeReaction = ref(false);
const hasLoveReaction = ref(false);
onMounted(()=>{
    showReactionIcons();
})


const showReactionIcons = ()=>{
    hasLikeReaction.value =   false ;
    hasLoveReaction.value =  false ;

    for(let reaction of props.post.reactions){
        if(reaction.type === 'like'){
            hasLikeReaction.value =   true ;
        }
        if (reaction.type == 'love') {
            hasLoveReaction.value =  true ;
        }

    }
}


//show comments
const showComments = ()=>{
    emit('onShowComments', props.post)
}



</script>

