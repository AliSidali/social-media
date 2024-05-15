<template>
<Head title="Profile" />
<AuthenticatedLayout>
    <div class="max-w-3xl mx-auto  h-full overflow-auto">
        <div v-show="showNotification && success" class="px-4 py-2 bg-emerald-400 text-white my-2 text-sm font-medium">
            {{ success }}
        </div>
        <div v-show="validationErrorsExist " class="flex justify-between px-4 py-2 bg-red-400 text-white my-2 text-sm font-medium">
            {{ errors.avatar }}
            <XMarkIcon class="w-5 cursor-pointer"  @click="showNotification=false"/>
        </div>
        <div class="bg-white border-b">

            <!-- Select cover image -->
            <div class="relative  group">
                <img class="w-full h-[200px] object-cover " :src=" coverImageSrc || user.cover_path || '/imgs/default_cover.jpg' " alt="">
                <div class="opacity-0 absolute right-3 top-3  group-hover:opacity-100">
                    <button v-if="!coverImageSrc" class="flex space-x-2  w-48 justify-center text-sm py-1 bg-gray-50 text-gray-800 hover:bg-gray-100">
                        <CameraIcon class="w-5" />
                        <span>Update cover image</span>
                        <input type="file" class="opacity-0 absolute inset-y-0 top-0 right-0.5 w-48  py-1" @change="onCoverChange">
                    </button>

                    <div v-else class="flex space-x-3">
                        <button @click="cancelCoverImage"  class="flex space-x-2   justify-center bg-gray-50 text-gray-800 text-sm px-2 py-1 hover:bg-gray-100">
                            <XMarkIcon class="w-5" />
                            <span>Cancel</span>
                        </button>

                        <button @click="submitSelectedImage('avatar')('cover')"  class="flex space-x-2   justify-center bg-gray-800 text-gray-100 text-sm px-2 py-1 hover:bg-gray-900">
                            <CheckCircleIcon class="w-5" />
                            <span>Submit</span>
                        </button>
                    </div>
                </div>

            </div>
            <div class="flex">

                <!-- Select avatar image -->
                <div class=" group ml-[60px] -mt-[60px] relative w-[120px] h-[120px] ">
                    <img class=" w-[120px] h-[120px] rounded-full " :src="avatarImageSrc || user.avatar_path || '/imgs/default_avatar.jpg'" alt="">
                    <button v-if="!avatarImageSrc" class="absolute  w-[120px] inset-0 flex items-center justify-center opacity-0 bg-black/50  rounded-full  group-hover:opacity-100">
                        <CameraIcon class="w-8 h-8 fill-gray-200 " />
                        <input type="file" class=" absolute inset-0 w-[120px] opacity-0" @change="onAvatarChange">
                    </button>

                    <div v-else class="flex flex-col absolute top-0 right-0 space-y-1">
                        <button @click="cancelAvatarImage"  class=" bg-red-500/80 rounded-full p-1 text-white text-sm  ">
                            <XMarkIcon class="w-5" />
                        </button>

                        <button @click="submitSelectedImage('avatar')"  class=" bg-green-500/80 rounded-full p-1 text-white text-sm ">
                            <CheckCircleIcon class="w-5" />
                        </button>
                    </div>
                </div>


                <div class="p-3 flex justify-between items-center flex-1">
                    <h2 class="font-bold text-lg ">{{ user.name }}</h2>
                    <PrimaryButton v-if="isUserProfile">
                        <PencilIcon class="w-5 mr-2" />
                        <span>Edit Profile</span>
                    </PrimaryButton>
                </div>
            </div>

        </div>

        <div class="w-full  px-2   sm:px-0">
            <TabGroup>
                <TabList class="flex py-1  bg-white ">

                    <Tab v-slot="{ selected }" as="template">
                        <TabItem text="Posts"  :selected="selected"/>
                    </Tab>
                    <Tab v-slot="{ selected }" as="template">
                        <TabItem text="Followers"  :selected="selected"/>
                    </Tab>
                    <Tab v-slot="{ selected }" as="template">
                        <TabItem text="Followings"  :selected="selected"/>
                    </Tab>
                    <Tab v-slot="{ selected }" as="template">
                        <TabItem text="Photos"  :selected="selected"/>
                    </Tab>
                    <Tab v-if="isUserProfile"   v-slot="{ selected }" as="template">
                        <TabItem text="My Profile"  :selected="selected"/>
                    </Tab>



                </TabList>

                    <TabPanels class="mt-2">

                    <TabPanel  class="bg-white p-3 shadow">
                        posts page
                    </TabPanel>
                    <TabPanel class="bg-white p-3 shadow">
                        followers page
                    </TabPanel>
                    <TabPanel class="bg-white p-3 shadow">
                        followings page
                    </TabPanel>
                    <TabPanel class="bg-white p-3 shadow">
                        Photos
                    </TabPanel>
                    </TabPanels>
                    <TabPanel v-if="isUserProfile">
                        <Edit :mustVerifyEmail="mustVerifyEmail" :status="status" />
                    </TabPanel>
                </TabGroup>
        </div>
    </div>
</AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "./Edit.vue";
import { usePage, Head, useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import TabItem  from "./Partials/TabItem.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { CameraIcon, CheckCircleIcon, PencilIcon, PhotoIcon, XMarkIcon } from "@heroicons/vue/24/solid";

const authUser  = usePage().props.auth.user;

const props = defineProps({
    errors: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    success: {
        type: String,
    },
    user: {
        type: Object
    }
});

const isUserProfile = computed(()=>{
    return authUser && authUser.id == props.user.id;
});


// updating cover photo
const coverImageSrc = ref('');
const avatarImageSrc = ref('');
const showNotification = ref(true);


const imagesForm = useForm({
        cover: null,
        avatar: null
    });


//Show selected cover image only in frontend
const onCoverChange = (evt)=>{
    imagesForm.cover  = evt.target.files[0];
    if (imagesForm.cover) {
        const reader = new FileReader()
        reader.onload = ()=> {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.cover )
    }
}

//Show selected avatar image only in frontend
const onAvatarChange = (evt)=>{
    imagesForm.avatar = evt.target.files[0];
    if(imagesForm.avatar){
        const reader = new FileReader();
        reader.onload = ()=>{
            avatarImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.avatar);
    }
}

//cancel frontend selected image
const cancelCoverImage = ()=>{
    imagesForm.cover = null;
    coverImageSrc.value = null;
}
const cancelAvatarImage = ()=>{
    imagesForm.avatar = null;
    avatarImageSrc.value = null;
}

//submit cover image to the backend
const submitSelectedImage = (image)=>{
    imagesForm.post(route('profile.updateImages'), {
        onSuccess: ()=>{
            if(image=="cover"){
                cancelCoverImage();
            }else{
                cancelAvatarImage();
            }
            setTimeout(()=>{
                showNotification.value=false;
            }, 3000)
        }
    });
}

// validation errors

const validationErrorsExist = computed(()=>{
    console.log(props.errors);
    return showNotification.value &&  (props.errors.cover || props.errors.avatar);
})


</script>


