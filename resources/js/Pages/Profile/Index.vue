<template>
<Head title="Profile" />

<AuthenticatedLayout>
    <div class="max-w-3xl mx-auto  h-full overflow-auto">
        <div class="bg-white border-b">
            <img class="w-full h-[200px] object-cover " src="/imgs/fb_cover.jpg" alt="">
            <div class="flex">
                <img class=" rounded-full w-[120px] h-[120px] -mt-[40px] ml-[50px]  " src="https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/avatars/38/38161213c5a33b84f4f4bc4c13077b2e15aae344_full.jpg" alt="">
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
import { usePage, Head } from "@inertiajs/vue3";
import { computed, ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import TabItem  from "./Partials/TabItem.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { PencilIcon } from "@heroicons/vue/24/solid";

const authdUser  = usePage().props.auth.user;

const props = defineProps({
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
    return authdUser && authdUser.id == props.user.id;
})
</script>


