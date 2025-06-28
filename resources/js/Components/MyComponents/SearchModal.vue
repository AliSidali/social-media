<template>
  <TransitionRoot  appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/10" />
      </TransitionChild>

      <div class="fixed h-full  inset-0  top-10">
        <div
          class="flex max-h-full  justify-center p-4 text-center"
        >

            <DialogPanel
              class="w-full max-h-[95%] max-w-xl transform  rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900 mb-4"
              >
              <div class="flex">
                <div class="my-auto  p-2 bg-gray-200 border border-gray-300 border-r-0 rounded-l">
                    <MagnifyingGlassIcon class="w-5" />
                </div>
                <TextInput @keyup.enter="globalSearch" v-model="newKeyword" type="text" placeholder="search" class="w-full rounded-l-none " />
              </div>
              </DialogTitle>
              <div class="mt-2 max-h-[80%] overflow-auto ">
                    <div  class="mb-2">
                        <h3 class="text-sm font-semibold border-b">Users</h3>
                        <UserListItem  v-for="(user, index) in searchResult.users" :key="index" :user="user" />
                        <div class="text-center py-3 text-gray-400" v-if="!searchResult.users.length">
                            <span >search for users</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <h3 class="text-sm font-semibold border-b">Groups</h3>
                        <GroupItem  v-for="(group, index) in searchResult.groups" :key="index" :group="group" />
                        <div class="text-center py-3 text-gray-400" v-if="!searchResult.groups.length">
                            <span >search for groups</span>
                        </div>
                    </div>
                    <div >
                        <h3 class="text-sm font-semibold border-b">Posts</h3>
                        <Link  :href="route('post.view', post.id)" v-for="(post, index) in searchResult.posts" :key="index" class="flex gap-3 items-center  cursor-pointer px-3 py-2  hover:bg-gray-100">
                            <img class="w-[32px] h-[32px] rounded-full " :src="'storage/'+post.user.avatar_path??'https://picsum.photos/200'" alt="">
                            <p v-html="post.body.slice(0, 40)+'...'"></p>
                        </Link>
                        <div class="text-center py-3 text-gray-400" v-if="!searchResult.posts.length">
                            <span >search for posts</span>
                        </div>
                    </div>
                    <div  id="loadIntersect" class="mb-4"></div>


              </div>


            </DialogPanel>

        </div>
      </div>
    </Dialog>
  </TransitionRoot>


</template>

<script setup>
import { onMounted, ref, watch, nextTick } from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'
import TextInput from '@/Components/TextInput.vue';
import UserListItem from '@/Components/MyComponents/UserListItem.vue';
import GroupItem from '@/Components/MyComponents/GroupItem.vue';
import {MagnifyingGlassIcon} from '@heroicons/vue/24/solid';
import axiosClient from '@/axiosClient';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { helpers } from '@/helpers';


const {hasUrlQueryParam} = helpers();

const props = defineProps({
    modelValue:Boolean
})
const emit = defineEmits(['onSearchModelClose']);

const newKeyword = ref('');
const searchResult = ref({
    users:[],
    groups:[],
    posts:[],
    nextUrl: ""

});
const isOpen = ref(false)


function closeModal() {
  isOpen.value = false;
  emit('onSearchModelClose');
}

const searchInit = ()=>{
    searchResult.value = {
    users:[],
    groups:[],
    posts:[],
    nextUrl: ""

}
}

watch(()=>props.modelValue,
    ()=>{
        isOpen.value = props.modelValue
    },
    { immediate: true }
)



const globalSearch = (evt)=>{

    if(evt){
        searchInit();
        searchResult.value.nextUrl=route('globalSearch', encodeURIComponent(newKeyword.value));
    }

   axios.get(searchResult.value.nextUrl).then(({data})=>{


       if(!hasUrlQueryParam(searchResult.value.nextUrl, 'page')){
            searchResult.value.users = data.users;
            searchResult.value.groups = data.groups;
       }
       searchResult.value.posts = [...searchResult.value.posts, ...data.posts.data];
       searchResult.value.nextUrl = data.posts.next_page_url;





    })
}


onMounted(async()=>{

    // console.log(isOpen.value);

await nextTick(); //waiting for  DOM to render because of v-show

const load = document.getElementById('loadIntersect')

    const observer =  new IntersectionObserver((entries)=>{
        entries.forEach((entry)=>{
            if(entry.isIntersecting && searchResult.value.nextUrl){
                    globalSearch();

                }

        })
    });

     observer.observe(load);

})





</script>
