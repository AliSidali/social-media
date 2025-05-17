<template>
    <TextInput v-model="searchKeyword" class="w-full" placeholder="Search for friends..." />
    <div class="px-2  overflow-auto flex-1 mt-4">
        <div v-if="false" class="text-gray-400 text-center">
            Your don't have any friends yet.
        </div>
        <div v-else>
            <UserListItem v-for="(user, index) in followings"  :key="index" :user="user" :href="route('profile.index', user.username)"/>
        </div>
    </div>
</template>
<script setup>
import UserListItem from '@/Components/MyComponents/UserListItem.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    followings: Array
})

const searchKeyword = ref('');
</script>
