<template>
    <div class="bg-white py-3 px-5 border rounded mb-3">

        <!-- POST HEAD -->
        <div class="flex items-center gap-2 mb-3">
            <a href="#" class="border-2 rounded-full hover:border-blue-500  transition-all">
                <img class="w-[40px] rounded-full" :src="post.user.avatar" alt="">
            </a>
            <div class="ml-3">
                <h4 class="font-bold">
                   <a href="#" class="hover:underline transition-all"> {{ post.user.name }}</a>
                   <template v-if="post.group">
                        >
                       <a  href="#" class="hover:underline transition-all">{{ post.group.name }}</a>
                   </template>
                </h4>
                <small class="text-gray-400">{{ post.created_at }}</small>
            </div>
        </div>

        <!-- POST DESCRIPTION -->
        <div class="mb-3">
            <Disclosure v-slot="{open}">
                <div v-if="!open" v-html="post.body.substring(0, 100)+'...'" />

                <DisclosurePanel>
                    <div v-html="post.body" />
                </DisclosurePanel>

                <div class="flex justify-end">
                    <DisclosureButton class="text-blue-500 hover:underline">
                        {{  open ? 'Read  Less': 'Read More' }}
                    </DisclosureButton>
                </div>

            </Disclosure>
        </div>

        <!-- POST ATTACHEMENTS -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 mb-3">
            <div v-for="(attachment, index) in post.attachments" :key="index" class="relative group">
                <div v-if="isImage(attachment)" class="  h-52">
                    <img  :src="attachment.url" class="h-full w-full" alt="">
                </div>
                <div v-else class="flex flex-col justify-center items-center text-gray-500 h-52 bg-blue-100">
                    <DocumentIcon  class=" w-20 " />
                    <h4 class="font-semibold text-lg">{{ attachment.name }}</h4>
                </div>
                <button class="p-2 text-white bg-gray-800 rounded absolute top-2 right-2 opacity-0  hover:bg-gray-600 group-hover:opacity-100">
                    <ArrowDownTrayIcon class="w-4"/>
                </button>
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
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import { ArrowDownTrayIcon, HandThumbUpIcon, DocumentIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/solid'

defineProps({
    post: Object
})

const isImage = (attachment)=>{
    let mime = attachment.mime.split('/');
    return mime[0].toLowerCase() === 'image';
}
</script>
