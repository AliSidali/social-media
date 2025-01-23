<template>
    <TextInput v-model="forModelError" class="w-full" placeholder="Search for friends..." />
    <div class="px-2  overflow-auto flex-1 mt-4">
        <div v-if="false" class="text-gray-400 text-center">
            Your don't have any friends yet.
        </div>
        <div v-else>
            <UserListItem image="https://picsum.photos/200" name="Abd elrezak" />
            <UserListItem image="https://picsum.photos/200" name="Hadjer sidali" />
        </div>
    </div>
</template>
<script setup>
import UserListItem from '@/Components/MyComponents/UserListItem.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
const forModelError = ref('');

</script>
