<template>
    <div class="bg-white py-3 px-5 border rounded mb-3">
        <!-- POST HEAD -->
        <div class="flex justify-between">
            <PostUserHeader  :post="post"/>
            <!-- the three dots section -->
            <EditDeleteDropdown :post="post" @edit="openEditModal" @delete="deletePost"/>
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
            <div class="relative flex items-center group">
                <div class="" v-for="(type, index) in existingReactionTypes" :key="index">
                    <img  :src="`/storage/emojis/${type}.png`"  class="w-5 object-contain" />
                </div>
                <div v-if="post.reactions.length > 0">
                    <span class="ml-2">{{ post.reactions[0].username }} and</span>
                    <span class="ml-2">{{ post.reactions.length-1 }} others</span>
                </div>
                <!-- display list of users that have reaction -->
                <div class="absolute pointer-events-none top-6 -left-3 z-10 text-sm bg-black/70 p-2 w-40 rounded-lg text-white opacity-0 group-hover:pointer-events-auto group-hover:opacity-100">
                    <a :href="route('profile.index', reaction.username)" v-for="(reaction,index) in post.reactions" :key="index" class="flex gap-4 mb-2 z-30 hover:underline">
                        <span><img :src="reaction.image" class="w-5 object-contain bg-white rounded-full" alt=""> </span>
                        <span>{{ reaction.username }}</span>
                    </a>
                </div>

            </div>
            <div class="flex gap-1">
                <span>{{ post.post_comments_num }}</span>
                <ChatBubbleOvalLeftIcon class="w-5" />
            </div>

        </div>
        <!-- reactions and comment buttons -->
        <div class=" flex mb-3 gap-2">
            <div class="flex-1 relative group" >
                <div   class="absolute transform top-0 scale-0 left-1/2 -translate-x-1/2 px-2 bg-gray-100 flex  gap-4 border rounded shadow-sm  py-1 justify-center group-hover:-top-14 group-hover:scale-100 transition-all duration-500 ease-out">
                    <span  @click="sendPostReaction('like')" class=" w-14 h-14 text-white animate-bounce rounded-full bg-blue-600 p-2 cursor-pointer hover:scale-125 transition-all duration-500"> <HandThumbUpIcon class=" w-10 "/></span>
                    <span  @click="sendPostReaction('love')" class=" w-14 h-14 text-white  animate-bounce rounded-full bg-red-600 p-2 cursor-pointer hover:scale-125 transition-all duration-500"><HeartIcon  class="w-10"/> </span>
                    <span  @click="sendPostReaction('lamour')" class=" w-14 h-14 rounded-full animate-bounce cursor-pointer"><img src="/storage/emojis/lamour.png"  class=" object-contain" /></span>

                </div>
                <button
                    @click="sendPostReaction('like')"
                    class="flex z-10 w-full items-center justify-center gap-2 text-gray-800  py-2 rounded-lg "
                    :class="post.has_current_user_reaction ? 'bg-sky-100 hover:bg-sky-200' : 'bg-gray-100 hover:bg-gray-200'"
                >
                    <HandThumbUpIcon class="w-6 text-blue-700"/>
                    <span>{{ translations.like_button }}</span>
                </button>
            </div>

            <button @click="showComments" class="flex flex-1 items-center justify-center gap-2 bg-gray-100 py-2  rounded-lg hover:bg-gray-200">
                <ChatBubbleLeftRightIcon class="w-6" />
                <span>{{ translations.comment_button }}</span>
            </button>
        </div>


    </div>

</template>
<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import { ArrowDownTrayIcon, ChatBubbleLeftRightIcon, PencilIcon, TrashIcon, PaperClipIcon,  FaceFrownIcon, ChatBubbleOvalLeftIcon } from '@heroicons/vue/24/outline';
import {FaceSmileIcon, HandThumbUpIcon, HeartIcon} from '@heroicons/vue/24/solid'
import { MenuItem } from '@headlessui/vue'
import PostUserHeader from './PostUserHeader.vue';
import ReadMoreLess from './ReadMoreLess.vue';
import EditDeleteDropdown from './EditDeleteDropdown.vue';
import {helpers} from '@/helpers';
import axiosClient from '@/axiosClient';
import { computed, onMounted, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';


const {isImage} = helpers();

const props = defineProps({
    post: Object,
})

const page = usePage().props;
const user = page.auth.user;
const translations = page.translations;



//SHOWING EDIT POST MODAL
const emit = defineEmits(['editClick', 'onAttachmentClick', 'onShowComments', 'postValue']);
const openEditModal = ()=>{

    emit('editClick', props.post);
};


//DELETE POST

const deletePost = ()=>{
    if(window.confirm(translations.delete_post_alert)){
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


    });
}
const existingReactionTypes = computed(()=>{
    let reactionTypes = [];
    for(let reaction of props.post.reactions){

        if(!reactionTypes.includes(reaction.type)){
            reactionTypes = [...reactionTypes, reaction.type]
        }
    }
    return reactionTypes;
})


//show comments
const showComments = ()=>{
    emit('onShowComments', props.post)
}


</script>

