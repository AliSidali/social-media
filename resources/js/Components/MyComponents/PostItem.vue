<template>
    <div class="bg-white py-3 px-5 border rounded mb-3">
        <!-- POST HEAD -->
        <div class="flex justify-between">
            <PostUserHeader  :post="post"/>

            <!-- the three dots section -->
            <Menu as="div" class="relative inline-block ">
                <div>
                    <MenuButton
                    class="w-8 h-8 rounded-full  flex justify-center items-center hover:bg-black/5 transition"
                    >
                    <EllipsisVerticalIcon class="w-5 h-5" />
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                    class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                    >
                    <div class="px-1 py-1 ">
                        <MenuItem v-slot="{ active }">
                        <button
                            :class="[
                            active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                            @click="openEditModal"
                        >
                            <PencilIcon class="w-5 h-5 mr-2" />
                            Edit
                        </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <button
                            :class="[
                            active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"

                            @click="deletePost"
                        >
                            <TrashIcon class="w-5 h-5 mr-2" />
                            Delete
                        </button>
                        </MenuItem>
                    </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>


        <!-- POST DESCRIPTION -->
        <div class="mb-3">

            <Disclosure v-slot="{open}">
                <div class="ck-content-output" v-if="!open" v-html="post.body.substring(0, 200)" />

                <DisclosurePanel>

                    <div class="ck-content-output" v-html="post.body" />
                </DisclosurePanel>

                <div v-if="post.body.length > 200" class="flex justify-end">
                    <DisclosureButton class="text-blue-500 hover:underline">
                        {{  open ? 'Read  Less': 'Read More' }}
                    </DisclosureButton>
                </div>

            </Disclosure>
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
                    <a :href="route('attachment.download', attachment)" class="p-2 text-white bg-gray-800 rounded absolute top-2 right-2 opacity-0  hover:bg-gray-600 group-hover:opacity-100">
                        <ArrowDownTrayIcon class="w-4"/>
                    </a>
                </div>

            </div>

        </div>

        <!-- LIKE AND COMMENT -->
        <div class="flex mb-3 gap-2">
            <button class="flex flex-1 items-center justify-center gap-2 text-gray-800 bg-gray-100 py-2 rounded-lg hover:bg-gray-200">
                <HandThumbUpIcon class="w-6"/>
                <span>Like</span>
            </button>
            <button class="flex flex-1 items-center justify-center gap-2 bg-gray-100 py-2  rounded-lg hover:bg-gray-200">
                <ChatBubbleLeftRightIcon class="w-6" />
                <span>Comment</span>
            </button>
        </div>



    </div>

</template>
<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import { ArrowDownTrayIcon, HandThumbUpIcon, ChatBubbleLeftRightIcon, EllipsisVerticalIcon, PencilIcon, TrashIcon, PaperClipIcon } from '@heroicons/vue/24/solid';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import PostUserHeader from './PostUserHeader.vue';
import {isImage} from '@/helpers';


import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    post: Object,
})





//SHOWING EDIT POST MODAL
const emit = defineEmits(['editClick', 'onAttachmentClick']);
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
    emit('onAttachmentClick', post, index);
}



</script>
