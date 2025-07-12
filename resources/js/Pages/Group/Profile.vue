<template>
    <Head :title="group.slug" />
    <AuthenticatedLayout>
        <div class="w-full bg-white mx-auto h-full overflow-auto lg:w-[60%] pb-10 dark:bg-slate-800 dark:text-gray-100">
            <div v-if="message.success"  class="bg-green-900 text-white py-1 px-2  text-center font-semibold">
                   {{message.success}}
            </div>
            <div class="relative group/cover  h-[200px]">

                <div class="h-full" :class="newCoverImage || group.cover_path ? '' : ' opacity-50 bg-gradient-to-b from-gray-100 to-gray-400 hover:opacity-100'  ">
                    <img :src="newCoverImage ?? group.cover_path" alt="" class="w-full h-full object-cover">
                </div>
                <div v-if="isCurrentUserAdmin">
                    <div v-if="!isCoverChanged" class="absolute opacity-0 top-2 right-4 group-hover/cover:opacity-100 ">
                        <div class="relative px-2 py-1 bg-indigo-600 text-white rounded  hover:bg-indigo-800">

                            <span class="flex gap-2 text-sm capitalize">
                            <CameraIcon class="w-5 " />
                                {{ translations.update_cover }}
                            </span>
                            <input @change="updateCover" type="file" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div v-else class="absolute flex gap-4 top-2 right-2 ">
                        <button @click="cancelChangingImages" class="flex gap-1 bg-gray-700 text-white px-1 py-0.5 text-sm hover:bg-gray-900">
                            <XMarkIcon class="w-5" />
                            <span class="capitalize">{{ translations.cancel_button }}</span>
                        </button>

                        <button @click="sendGroupImages" class="flex bg-gray-200 px-1 py-0.5 text-sm gap-1 hover:bg-gray-300">
                            <CheckCircleIcon class="w-5" />
                            <span class="capitalize">{{ translations.send_button }}</span>
                        </button>
                    </div>
                </div>
            </div>


            <div class="flex py-4 px-8 border-b ">
                <div class="relative z-10  mr-4 -mt-20 ">
                    <img class="w-40 h-32 rounded-full " :src="newThumbnailImage ?? group.thumbnail_path" alt="">
                    <div v-if="isCurrentUserAdmin" >
                        <div v-if="!isThumbnailChanged" class="opacity-0  hover:opacity-100">
                            <div class="absolute w-5 top-[40%] left-[40%] z-10">
                              <input @change="updateThumbnail" name="thumbnail" type="file" class="absolute opacity-0 w-5  cursor-pointer">
                              <CameraIcon class=" text-white" />
                            </div>

                            <div class="absolute  rounded-full inset-0 bg-black/25"></div>
                        </div>
                        <div v-else>
                            <button @click="sendGroupImages" class="absolute right-0 top-0 p-1 bg-green-900/75 text-white rounded-full"><CheckIcon class="w-4" /> </button>
                            <button @click="cancelChangingImages" class="absolute right-0 top-7 p-1 bg-red-900/75 text-white rounded-full"><XMarkIcon class="w-4" /> </button>
                        </div>
                    </div>
                </div>

                <div class="flex   justify-between w-full">
                    <h3 class="font-black">{{ group.name }}</h3>
                    <button v-if="isCurrentUserAdmin" @click="isOpenModal = true"  class="flex text-sm items-center gap-2 px-2 py-1 bg-cyan-700 text-white rounded">
                        <span>{{ translations.invitation }}</span>
                    </button>
                    <button v-else-if="!group.role && group.autoApproval" @click="joinToGroup"  class="flex text-sm items-center gap-2 px-2 py-1 bg-cyan-700 text-white rounded">
                        <span>{{ translations.group_join }}</span>
                    </button>
                    <button v-else-if="!group.role && !group.autoApproval" @click="joinToGroup"  class="flex text-sm items-center gap-2 px-2 py-1 bg-cyan-700 text-white rounded">
                        <span>Request to Join</span>
                    </button>
                    <div v-else-if="group.status === 'pending'" class="bg-gray-100 flex items-center h-10 px-4 rounded-md text-gray-500">
                        <span class="capitalize">request sent</span>
                    </div>
                </div>
            </div>


            <div class="px-2 py-2 ">
                <TabGroup >
                    <TabList class="flex">
                      <Tab
                        as="template"
                        v-slot="{ selected }"
                      >
                        <TabItem text="posts" :selected="selected"

                        />
                      </Tab>

                      <Tab
                        as="template"
                        v-slot="{ selected }"
                        v-if="group.status === 'approved'"
                      >
                        <TabItem text="followers" :selected="selected" />
                      </Tab>
                      <Tab
                        as="template"
                        v-slot="{ selected }"
                        v-if="isCurrentUserAdmin"
                      >
                        <TabItem text="pending users" :selected="selected" :alert="pendingUsers.length"/>
                      </Tab>
                      <Tab
                        as="template"
                        v-slot="{ selected }"
                        v-if="isCurrentUserAdmin"
                      >
                        <TabItem text="rejected users" :selected="selected" :alert="pendingUsers.length"/>
                      </Tab>
                      <Tab
                        as="template"
                        v-slot="{ selected }"
                      >
                        <TabItem text="photos" :selected="selected" />
                      </Tab>

                      <Tab
                        as="template"
                        v-slot="{ selected }"
                      >
                        <TabItem text="about" :selected="selected" />
                      </Tab>


                    </TabList>

                    <TabPanels class="mt-2">
                      <TabPanel
                        :class="[
                          'rounded-xl bg-white p-3 dark:bg-slate-900',
                          'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                        ]"
                      >
                        <template v-if="groupPosts">
                          <CreatePost :group_id="group.id"/>
                          <PostList :posts="groupPosts" />
                        </template>
                        <div v-else>
                            you don't have permission to see this group posts.
                        </div>

                      </TabPanel>

                      <TabPanel
                        :class="[
                          ' bg-white px-3 py-6 dark:bg-slate-800'
                        ]"
                        v-if="group.status === 'approved'"
                      >

                            <h3 class="text-sm font-medium grid grid-cols-2 gap-2">
                                <div v-for="(user, index) in approvedUsers" :key="index" class="flex justify-between items-center px-1 border-2 border-transparent shadow   hover:border-indigo-300 dark:bg-slate-900" >
                                    <UserListItem :href="route('profile.index', user.username)" :user="user" class="hover:bg-white"/>
                                    <div class="flex gap-2 items-center">
                                      <button v-if="isCurrentUserAdmin && user.id !== currentUser.id && user.id !== group.user_id" @click="approveUser(user.id, 'rejected')" class="bg-orange-500 text-xs text-white px-4 py-2 rounded hover:bg-orange-700 capitalize">reject</button>
                                      <Dropdown :isDisabled=" user.id == currentUser.id || user.id == group.user_id" width="48">
                                        <template #trigger>
                                            <button  class="flex items-center gap-1  cursor-pointer border border-gray-400 text-xs py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800" :class="{'text-gray-400 cursor-auto hover:bg-white' : user.id == currentUser.id || user.id == group.user_id}">
                                                {{user.role}}
                                                <ChevronDownIcon class="w-4" />
                                            </button>
                                        </template>
                                        <template #content>
                                            <div class="flex flex-col text-center w-28">
                                                <div @click="changeRole(user.id, 'admin')" class="w-full text-center py-2  cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800">
                                                    Admin
                                                </div>
                                                <div @click="changeRole(user.id, 'user')" class="w-full text-center py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800">
                                                    User
                                                </div>
                                            </div>
                                        </template>
                                      </Dropdown>
                                    </div>
                                </div>
                            </h3>


                      </TabPanel>

                      <TabPanel
                         v-if="isCurrentUserAdmin"
                        :class="[
                          'rounded-xl bg-white p-3',
                          'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 dark:bg-slate-800',
                        ]"
                      >
                            <div class="grid grid-cols-2 text-sm font-medium gap-2">
                                <div v-for="(user, index) in pendingUsers" :key="index" class="flex justify-between items-center px-4 border-2 border-transparent shadow  dark:bg-slate-900" >
                                    <UserListItem :href="route('profile.index', user.username)" :user="user" class="hover:bg-white"/>
                                    <div class="flex gap-2">
                                        <button @click="approveUser(user.id, 'approved')" class="bg-gray-800 text-xs text-white px-4 py-2 rounded hover:bg-gray-900 capitalize">approve</button>
                                        <button @click="approveUser(user.id, 'rejected')" class="bg-orange-500 text-xs text-white px-4 py-2 rounded hover:bg-orange-900 capitalize">reject</button>
                                    </div>
                                </div>
                            </div>


                      </TabPanel>
                      <TabPanel
                        v-if="isCurrentUserAdmin"
                        :class="[
                          'rounded-xl bg-white p-3',
                          'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 dark:bg-slate-800',
                        ]"
                      >
                            <div  class="grid grid-cols-2 text-sm font-medium gap-2">
                                <div v-for="(user, index) in rejectedUsers" :key="index" class="flex justify-between items-center px-4 border-2 border-transparent shadow dark:bg-slate-900"  >
                                    <UserListItem :href="route('profile.index', user.username)" :user="user" class="hover:bg-white"/>
                                    <div class="flex gap-2">
                                        <button @click="approveUser(user.id, 'approved')" class="bg-gray-800 text-xs text-white px-4 py-2 rounded hover:bg-gray-900 capitalize">approve</button>
                                        <button @click="deleteGroupUser(user.id)" class="bg-red-800 text-xs text-white px-4 py-2 rounded hover:bg-red-900 capitalize">Delete</button>
                                    </div>
                                </div>
                            </div>



                      </TabPanel>
                      <TabPanel
                        :class="[
                          'rounded-xl bg-white p-3',
                          'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 dark:bg-slate-800',
                        ]"
                      >


                            <PhotosTab :attachments="attachments" />


                      </TabPanel>

                      <TabPanel
                        :class="[
                          'rounded-xl bg-white p-3',
                          'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 dark:bg-slate-800',
                        ]"
                      >

                        <template v-if="isCurrentUserAdmin">
                            <GroupForm :form="groupForm" :translations="translations" :validationErrors="validationErrors" />
                            <div class="text-end">
                                <button @click="updateGroup" class=" bg-cyan-700 py-2 px-4 rounded text-white">
                                    Edit
                                </button>
                            </div>
                        </template>
                        <div v-else v-html="group.about"></div>


                      </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
        <InviteUserModal v-model="isOpenModal" @onCloseModal="closeModal"/>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { PencilIcon, CameraIcon, XMarkIcon, CheckIcon, CheckCircleIcon, ChevronDownIcon } from '@heroicons/vue/24/solid';

import { TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import { computed, onMounted, reactive, ref, watch } from 'vue';
import axiosClient from '@/axiosClient';
import PostList from '@/Components/MyComponents/PostList.vue';
import TabItem from '@/Components/MyComponents/TabItem.vue';
import UserListItem from '@/Components/MyComponents/UserListItem.vue';
import Dropdown from '@/Components/Dropdown.vue';
import GroupForm from '@/Components/MyComponents/GroupForm.vue';
import CreatePost from '@/Components/MyComponents/CreatePost.vue';
import PhotosTab from "@/Components/MyComponents/PhotosTab.vue";
import InviteUserModal from "@/Components/MyComponents/InviteUserModal.vue";


const props = defineProps({
    group: Object,
    message: Object,
    pendingUsers: Object,
    approvedUsers: Object,
    groupPosts:Object,
    groupUsers: Object,
    attachments:Array
})



const page = usePage();
const translations = page.props.translations;
const currentUser = page.props.auth.user;
const newCoverImage = ref(null);
const newThumbnailImage = ref(null);
const isCoverChanged = ref(false);
const isThumbnailChanged = ref(false);
const images = {
    cover: null,
    thumbnail: null
}
const isOpenModal=ref(false);

const isCurrentUserAdmin  = computed(()=>{
    return props.group.role == 'admin';
})


//update group section
const validationErrors = ref({});
const groupForm = useForm({
    name: props.group.name,
    auto_approval:props.group.autoApproval? true: false,
    about:props.group.about?.replace(/<br\s\/>/g, '\n'),
});




const sendGroupImages = ()=>{
  axiosClient.post(route('group.updateImages', props.group.slug), {
      cover:   images.cover,
      thumbnail : images.thumbnail
  },{
  headers: {
      'Content-Type': 'multipart/form-data'
  }}).then(({data})=>{
    isCoverChanged.value = false;
    isThumbnailChanged.value = false;
    props.message.success = data.success;

  })
}




const updateCover = async(e)=>{
  images.cover = e.target.files[0];
  newCoverImage.value = await readFile(images.cover);
  isCoverChanged.value = true;
}




const updateThumbnail = async(e)=>{
    images.thumbnail = e.target.files[0];
    newThumbnailImage.value = await readFile(images.thumbnail);
    isThumbnailChanged.value = true;
}

const cancelChangingImages = ()=>{
    newCoverImage.value = null;
    newThumbnailImage.value = null;
    isCoverChanged.value = false;
    isThumbnailChanged.value = false;
}

const closeModal = ()=>{
    isOpenModal.value = false;
}


const readFile = (file)=>{
    return new Promise((res, rej)=>{
        const reader = new FileReader();
        reader.onload = ()=>{
            res(reader.result);
        }
    reader.readAsDataURL(file);

    })
    }

watch(()=>props.message.success, ()=>{
    setTimeout(()=>{
        props.message.success = null;
    }, 3000)
});


//request to join to group
const joinToGroup = ()=>{
    const form = useForm({});

    form.post(route('group.join', props.group.id));
}

//approve and disapprove a request
const approveUser = (userId, action)=>{
    const form = useForm({
        userId : userId,
        action : action
    });
    form.post(route('group.approveRequest', props.group.id));
}

//CHANGE ROLE FROM USER TO ADMIN OR VICE VERSA
const changeRole = (userId, role)=>{

    const form = useForm({
        user_id: userId,
        role:role
    });
    form.patch(route('group.updateRole', props.group.id))
}


//UPDATE GROUP IN ABOUT SECTION
const updateGroup = ()=>{
    groupForm.put(route('group.update', props.group.slug))
}

const rejectedUsers = computed(()=>{
    return props.groupUsers.filter(user=>user.status === 'rejected')
})

const deleteGroupUser = (userId)=>{
    if(!window.confirm("are you sure you want delete the user")){
        return false;
    }
    const form = useForm({
        userId: userId
    })


   form.delete(route('groupUser.destroy', props.group.slug));
}




</script>
