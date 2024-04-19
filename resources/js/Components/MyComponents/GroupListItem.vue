<template>
<TextInput class="w-full" placeholder="Search for groups..." />
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
</template>
<script setup>
import TextInput from '@/Components/TextInput.vue';
import GroupItem from '@/Components/MyComponents/GroupItem.vue';

</script>
