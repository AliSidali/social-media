<template>
    <div class="p-3 bg-gray-200 rounded-t border mb-3 dark:bg-slate-800">

        <div  @click="showModal=true"  class="w-full mb-3  bg-white border border-gray-200 py-2 px-3 rounded-md  text-gray-500 dark:bg-slate-900">
            {{ translations.add_post_placeholder }}

        </div>


        <PostModal v-model="showModal" :group_id="group_id" />
    </div>
</template>
<script setup>
import {  ref } from 'vue';
import PostModal from './PostModal.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axiosClient from '@/axiosClient';

const props = defineProps({
    success: {
        type: String
    },
    group_id:{
        type:Number,
        default:null
    }
})
const  page = usePage().props;
const translations = page.translations;

//SHOW MODAL FOR CREATING POST

const showModal = ref(false);









</script>
