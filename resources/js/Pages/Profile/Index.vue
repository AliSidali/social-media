<template>
<Head title="Profile" />
{{ errors }}
{{ validationErrorsExist }}
<AuthenticatedLayout>
    <div class="max-w-3xl mx-auto  h-full overflow-auto">
        <div v-show="showNotification && status === 'cover-image-update'" class="px-4 py-2 bg-emerald-400 text-white my-2 text-sm font-medium">
            Your cover image has been updated
        </div>
        <div v-show="validationErrorsExist " class="flex justify-between px-4 py-2 bg-red-400 text-white my-2 text-sm font-medium">
            {{ errors.cover }}
            <XMarkIcon class="w-5 cursor-pointer"  @click="showNotification=false"/>
        </div>
        <div class="bg-white border-b">
            <div class="relative  group">
                <img class="w-full h-[200px] object-cover " :src=" coverImageSrc || authUser.cover_path || '/imgs/fb_cover.jpg' " alt="">
                <div class="opacity-0 absolute right-3 top-3  group-hover:opacity-100">
                    <button v-if="!coverImageSrc || isSubmitted" class="flex space-x-2  w-48 justify-center text-sm py-1 bg-gray-50 text-gray-800 hover:bg-gray-100">
                        <CameraIcon class="w-5" />
                        <span>Update cover image</span>
                        <input type="file" class="opacity-0 absolute inset-y-0 top-0 right-0.5 w-48  py-1" @change="selectCoverImage">
                    </button>

                    <div v-else class="flex space-x-3">
                        <button @click="cancelSelectedCover"  class="flex space-x-2   justify-center bg-gray-50 text-gray-800 text-sm px-2 py-1 hover:bg-gray-100">
                            <XMarkIcon class="w-5" />
                            <span>Cancel</span>
                        </button>

                        <button @click="submitSelectedCover()"  class="flex space-x-2   justify-center bg-gray-800 text-gray-100 text-sm px-2 py-1 hover:bg-gray-900">
                            <XMarkIcon class="w-5" />
                            <span>Submit</span>
                        </button>
                    </div>
                </div>

            </div>
            <div class="flex">
                <img class=" rounded-full w-[120px] h-[120px] -mt-[40px] ml-[50px]  z-10" src="https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/avatars/38/38161213c5a33b84f4f4bc4c13077b2e15aae344_full.jpg" alt="">
                <div class="p-3 flex justify-between items-center w-full">
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
                    <Tab v-if="isUserProfile"   v-slot="{ selected }" as="template">
                        <TabItem text="About"  :selected="selected"/>
                    </Tab>
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



                </TabList>

                <TabPanels class="mt-2">
                <TabPanel v-if="isUserProfile">
                    <Edit :mustVerifyEmail="mustVerifyEmail" :status="status" />
                </TabPanel>
                <TabPanel  class="bg-white p-3 shadow">
                posts page
                </TabPanel>
                <TabPanel class="bg-white p-3 shadow">
                followers page
                </TabPanel>
                </TabPanels>
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
import { CameraIcon, PencilIcon, XMarkIcon } from "@heroicons/vue/24/solid";

const authUser  = usePage().props.auth.user;

const props = defineProps({
    errors: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
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
const isSubmitted = ref(false);
const showNotification = ref(true);


const imagesForm = useForm({
        cover: null,
        avatar: null
    });


//Show selected image only in frontend
const selectCoverImage = (evt)=>{
    imagesForm.cover  = evt.target.files[0];
    if (imagesForm.cover) {
        const reader = new FileReader()
        reader.onload = ()=> {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.cover )
    }
}

//cancel frontend selected image
const cancelSelectedCover = ()=>{
    coverImageSrc.value = null;
}


//submit cover image to the backend
const submitSelectedCover = ()=>{
    imagesForm.post(route('profile.updateImages'), {
        onSuccess: ()=>{
            isSubmitted.value = true;
            setTimeout(()=>{
                showNotification.value=false;
            }, 3000)
        }
    });
}

// validation errors

const validationErrorsExist = computed(()=>{
    return showNotification.value &&  Object.keys(props.errors).length > 0;
})


</script>


