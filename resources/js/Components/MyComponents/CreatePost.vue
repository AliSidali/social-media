<template>
    <div class="p-3 bg-white rounded-t border mb-3">
<<<<<<< HEAD

        <div  @click="showModal=true"  class="w-full mb-3  border-2 border-gray-200 py-2 px-3 rounded-md text-gray-500">
            click here to add new post
=======
        <div v-show="success && showNotifcation" class="px-4 py-2 bg-emerald-400 text-white my-2 text-sm  font-medium">
            {{ success }}
        </div>
        <div v-for="(error, index) in page.props.errors" :key="index" class="px-4 py-2 bg-red-400 text-white my-2 text-sm  font-medium">
            {{ error }}
        </div>
        <InputTextarea v-model="newPostForm.body" @click="postCreating=true"  placeholder="click here to add new post"  class="w-full mb-3" rows="1" autoResize />
        <div v-if="postCreating" class="flex justify-between">
            <div class="">
                <button class="relative rounded bg-blue-800 text-white px-4 py-2 text-sm ">
                    Attach Files
                    <input type="file" class="absolute inset-0  opacity-0">
                </button>
            </div>
            <button class="rounded bg-blue-800 text-white px-4 py-2 text-sm" @click="submit">Submit</button>
>>>>>>> 39623f4 (implement post creation)
        </div>

        <PostModal v-model="showModal" :post="newPost" />
    </div>
</template>
<script setup>
<<<<<<< HEAD
import {  usePage } from '@inertiajs/vue3';
import {  ref } from 'vue';
import InputTextarea from './InputTextarea.vue';
import PostModal from './PostModal.vue';
=======
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import InputTextarea from './InputTextarea.vue';
>>>>>>> 39623f4 (implement post creation)

const props = defineProps({
    success: {
        type: String
    }
})
const page = usePage();
<<<<<<< HEAD

//SHOW MODAL FOR CREATING POST

const showModal = ref(false);
const newPost = ref({
    body:'',
    user:page.props.auth.user
})


//const showNotifcation = ref(true);




=======
>>>>>>> 39623f4 (implement post creation)


const newPostForm = useForm({
    body: ''
});
const showNotifcation = ref(true);


const submit = ()=>{
    newPostForm.post(route('post.store'), {
        onSuccess: ()=>{
            newPostForm.reset();
            setTimeout(()=>{
                showNotifcation.value=false;
            }, 3000);
            showNotifcation.value=true;

        }

    });
}



</script>
