<template>
    <div class="p-3 bg-white rounded-t border mb-3">

        <div  @click="showModal=true"  class="w-full mb-3  border-2 border-gray-200 py-2 px-3 rounded-md text-gray-500">
            click here to add new post
        </div>

        <PostModal v-model="showModal" :post="newPost" />
    </div>
</template>
<script setup>
import {  usePage } from '@inertiajs/vue3';
import {  ref } from 'vue';
import InputTextarea from './InputTextarea.vue';
import PostModal from './PostModal.vue';

const props = defineProps({
    success: {
        type: String
    }
})
const page = usePage();

//SHOW MODAL FOR CREATING POST

const showModal = ref(false);
const newPost = ref({
    body:'',
    user:page.props.auth.user
})


//const showNotifcation = ref(true);






</script>
