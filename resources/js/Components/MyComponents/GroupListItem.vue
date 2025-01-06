<template>
    <div class="flex gap-2">
        <TextInput v-model="forModelError" class="w-full" :placeholder="translations.group_search" />
        <button @click="showGroupModal = true" class=" bg-indigo-500 text-white text-sm py-1 px-2 rounded w-40 hover:bg-indigo-600">{{ translations.new_group_button }}</button>

    </div>

    <div class="flex-1  px-2  overflow-auto mt-4">
        <div v-if="groups.length == 0" class="text-gray-400 text-center">
            {{ translations.no_group }}
        </div>
        <div v-else>
            <GroupItem v-for="(group, index) in groups"  :key="index" :group="group"/>
        </div>
    </div>
    <GroupModal  v-model="showGroupModal" @closeGroupModal="closeGroupModal" @addGroup="onGroupCreate"/>

</template>
<script setup>
import TextInput from '@/Components/TextInput.vue';
import GroupItem from '@/Components/MyComponents/GroupItem.vue';
import { ref } from 'vue';
import GroupModal from './GroupModal.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    groups:Array

})


const page = usePage();
const translations = page.props.translations;

const forModelError = ref('');

const showGroupModal = ref(false);
const closeGroupModal = (isOpen)=>{
    showGroupModal.value = isOpen;
}

const onGroupCreate = (group)=>{
    props.groups.unshift(group);

}


</script>
