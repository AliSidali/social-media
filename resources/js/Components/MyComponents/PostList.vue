<template>
    <div class="lg:overflow-auto lg:flex-1 ">
        <PostItem @editClick="openEditModal" v-for="(post, index) in posts" :key="index" :post="post" />
        <PostModal v-model="showEditModal" :post="editPost"/>
    </div>

</template>
<script setup>
import { ref } from 'vue';
import PostItem from './PostItem.vue'
import PostModal from './PostModal.vue';


defineProps({
    posts: Array,
})

const showEditModal = ref(false);
const editPost = ref({});
const openEditModal= (newpost)=>{
    showEditModal.value= true;
    editPost.value = newpost;
}
</script>
