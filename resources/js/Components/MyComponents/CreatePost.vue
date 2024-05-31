<template>
    <div class="p-3 bg-white rounded-t border mb-3">
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
        </div>
    </div>
</template>
<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import InputTextarea from './InputTextarea.vue';

const props = defineProps({
    success: {
        type: String
    }
})
const page = usePage();

const postCreating = ref(false);

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
