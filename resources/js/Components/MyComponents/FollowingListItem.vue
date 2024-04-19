<template>
    <TextInput class="w-full" placeholder="Search for groups..." />
    <div class="px-2  overflow-auto flex-1 mt-4">
        <div v-if="false" class="text-gray-400 text-center">
            Your don't have any friends yet.
        </div>
        <div v-else>
            <FollowingItem image="https://picsum.photos/200" title="Abd elrezak" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
            <FollowingItem image="https://picsum.photos/200" title="Hadjer sidali" />
        </div>
    </div>
</template>
<script setup>
import FollowingItem from '@/Components/MyComponents/FollowingItem.vue';
import TextInput from '@/Components/TextInput.vue';

</script>
