<template>
    <div class="lg:overflow-auto lg:flex-1 ">
        <PostItem v-for="(post, index) in posts" :key="index" :post="post" />
    </div>
</template>
<script setup>
import { ref } from 'vue'
import PostItem from './PostItem.vue'
defineProps({
    posts: Array
})
</script>
