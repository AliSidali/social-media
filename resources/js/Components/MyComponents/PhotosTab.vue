<template>
    <div class="grid grid-cols-4 gap-2">
        <div @click="displayPhotoModal(index)" v-for="(attachment, index) in attachments" :key="index" class="group relative aspect-square cursor-pointer">

            <a :href="route('attachment.download', attachment)" class="absolute opacity-0 text-white bg-gray-800 p-1 group-hover:opacity-100">
                <ArrowDownTrayIcon  class="w-5" />
            </a>
            <img :src="attachment.url" alt="" class="h-full rounded-lg">
            {{ attachment.name }}
        </div>
    </div>
    <PhotoModal v-model="isPhotoModalOpen" @onClosePhotoModal="isPhotoModalOpen = false" :attachments="attachments" :index="attachmentIndex"/>

</template>
<script setup>
import { ArrowDownTrayIcon } from '@heroicons/vue/24/solid';
import PhotoModal from "@/Components/MyComponents/PhotoModal.vue";
import { ref } from 'vue';


const props = defineProps({
    attachments:Array
})
const isPhotoModalOpen = ref(false);

const attachmentIndex = ref(0);
const displayPhotoModal = (index)=>{
    isPhotoModalOpen.value = true;
    attachmentIndex.value = index;
}


</script>
