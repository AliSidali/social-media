<template>
    <div class="flex gap-2">
        <TextInput v-model="forModelError" class="w-full" placeholder="Search for groups..." />
        <button @click="showGroupModal = true" class=" bg-indigo-500 text-white text-sm py-1 px-2 rounded w-40 hover:bg-indigo-600">New Group</button>
        {{ showGroupModal }}
    </div>

    <div class="flex-1  px-2  overflow-auto mt-4">
        <div v-if="false" class="text-gray-400 text-center">
            Your are not joined to any group
        </div>
        <div v-else>
            <GroupItem image="https://picsum.photos/200" title="Laravel Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
            <GroupItem image="https://picsum.photos/200" title="Vuejs Devolopers" content="Lorem ipsum dolor sit amet consectetur adipisicing elit." />
        </div>
    </div>
    <GroupModal  v-model="showGroupModal" @closeGroupModal="closeGroupModal"/>

</template>
<script setup>
import TextInput from '@/Components/TextInput.vue';
import GroupItem from '@/Components/MyComponents/GroupItem.vue';
import { ref } from 'vue';
import GroupModal from './GroupModal.vue';
const forModelError = ref('');

const showGroupModal = ref(false);
const closeGroupModal = (isOpen)=>{
    showGroupModal.value = isOpen;
}
</script>
