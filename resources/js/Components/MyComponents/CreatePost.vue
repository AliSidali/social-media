<template>
    <div class="p-3 bg-white rounded-t border mb-3">
        <div @click="postCreating = !postCreating" class=" border border-gray-300 rounded py-2 px-3 text-gray-500 mb-3">
            Click here to create new post...
        </div>
        <div v-if="postCreating" class="flex justify-between">
            <div class="">
                <button class="relative rounded bg-blue-800 text-white px-4 py-2 text-sm ">
                    Attach Files
                    <input type="file" class="absolute inset-0  opacity-0">
                </button>
            </div>
            <button class="rounded bg-blue-800 text-white px-4 py-2 text-sm">Submit</button>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

const postCreating = ref(false);

</script>
