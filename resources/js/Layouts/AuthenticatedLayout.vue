<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeftStartOnRectangleIcon, BellAlertIcon, MoonIcon } from '@heroicons/vue/24/outline';
import axiosClient from '@/axiosClient';
import SearchModal from '@/Components/MyComponents/SearchModal.vue';


const showingNavigationDropdown = ref(false);

const  page = usePage().props;
const isSearchModalOpen = ref(false);


const authUser = page.auth.user;
const notificationsData=page.auth.notificationsData;
const translations = page.translations;
const notificationsNum = ref(notificationsData.notReadNotificationNum);


const changeLanguage = (lang)=>{

    axiosClient.post(route('language.index', lang)).then(()=>{
        window.location.reload();
    })
}

const readNotifications = ()=>{
    axiosClient.get(route('notification.read')).then(()=>{
        notificationsNum.value = 0;
    });
}

const changeLightMode = ()=>{
    const htmlClasses = document.getElementsByTagName('html')[0].classList;
    if(htmlClasses.contains('dark')){
        htmlClasses.remove('dark');
    }else{
        htmlClasses.add('dark');
    }

}


</script>

<template>
    <div>
        <div class=" bg-gray-100 dark:bg-gray-900 h-full"  >
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">


                        <!-- Primary Navigation Menu -->
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                    />
                                </Link>
                            </div>

                        </div>


                        <!-- links in the right of the navbar -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">

                            <!-- search bar -->
                            <div @click="isSearchModalOpen=true" class="border border-gray-200 text-gray-500 w-32 px-2 py-1 rounded ">
                                <span>search</span>
                                {{ isSearchModalOpen }}
                            </div>


                            <!-- notifications dropdowns -->
                            <div class="ms-3 relative flex gap-1" v-if="authUser">
                                <Dropdown align="right" >
                                    <template #trigger>
                                        <span class="inline-flex  rounded-md">
                                            <button
                                                @click="readNotifications"
                                                type="button"
                                                class="relative inline-flex items-center bg-gray-100 p-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-500 dark:text-gray-400  dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                <BellAlertIcon class="w-5" />
                                                <span v-if="notificationsNum >0" class="absolute -end-1 -top-2 bg-red-600 px-2 py-0.5 text-white text-xs rounded-full">
                                                    {{ notificationsNum < 10 ? notificationsNum : "+"+10 }}
                                                </span>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class=" w-[600px]">
                                            <div class=" p-4 text-lg font-semibold bg-gray-100">
                                                <h3>Notifications</h3>
                                            </div>
                                            <div class="max-h-[75vh] overflow-auto">

                                                <!-- notification.created_by.is_user ?  route('profile.index', notification.created_by.parameter) : route('group.profile', notification.created_by.parameter) -->
                                                <DropdownLink v-for="(notification, index) in notificationsData.notifications" :key="index"  :href="notification.created_by.path" class="flex  gap-4  items-center ">
                                                    <img :src="notification.created_by.thumbnail_path ?? '/storage/defaults/avatar.png'" class="border w-10 h-10 rounded-full" alt="">
                                                    <div>
                                                        <div class=" gap-2">
                                                            <span class="font-semibold">{{ notification.title }}: </span>
                                                            <span> {{ notification.message }} </span>
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ notification.created_at }}
                                                        </div>
                                                    </div>
                                                </DropdownLink>
                                        </div>
                                        </div>
                                    </template>
                                </Dropdown>


                                <!-- language dropdowns -->
                                <Dropdown align="right" width="">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class=" uppercase  inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">

                                                {{ page.lang ?? 'FR' }}
                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>

                                        <div v-for="(lang,index) in page.languages" :key="index">
                                            <DropdownLink href="#"  class="uppercase" @click="changeLanguage(lang)"> {{ lang }} </DropdownLink>
                                        </div>

                                    </template>
                                </Dropdown>


                                <!-- authuser dropdown -->
                                <Dropdown :contentClasses="' w-72 '" align="right" >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md  ">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                {{ authUser.name }}



                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink class="flex items-center gap-3 border-b border-gray-200" :href="route('profile.index', {username:authUser.username})">
                                            <img :src="authUser.avatar_path ?? '/storage/defaults/avatar.png'" class="border w-10 h-10 rounded-full" alt="">
                                            <span class="text-2xl font-semibold"> {{ authUser.username }} </span>
                                        </DropdownLink>
                                        <DropdownLink class="flex items-center gap-3" :href="route('logout')" method="post" as="button">
                                            <ArrowLeftStartOnRectangleIcon class="w-7" />
                                            <span>{{ translations.logout }}</span>
                                        </DropdownLink>
                                        <DropdownLink href="#" as="button" class="flex   items-center gap-3" @click="changeLightMode">
                                            <MoonIcon class="w-7" />
                                            <span>Dark Mode</span>
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>


                            <div v-else>
                                <Link :href="route('login')">
                                    Login
                                </Link>
                            </div>


                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >

                    <!-- Responsive Settings Options -->
                    <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                        <template v-if="authUser">
                            <div >
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {{ authUser.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">{{ authUser.email }}</div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.index', {username:authUser.username})"> Profile </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </div>

                            <!-- search bar -->
                            <div @click="isSearchModalOpen=true" class="border border-gray-300 text-gray-500 w-full px-2 py-1 rounded ">
                                <span>search</span>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('login')">Login</Link>
                        </template>
                    </div>
                </div>
            </nav>

            <!-- Page ing -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />

                </div>
            </header>

            <!-- Page Content -->
            <main class="h-[86%]">

                <!-- GLOBAL SEARCH RESULTS -->
                <!-- <div class="mt-8 px-4 grid grid-cols-2 gap-3 mb-4 rounded-lg">
                    <div v-if="searchResult.users" class="bg-white p-3 max-h-[200px] overflow-auto shadow rounded-lg">
                        <h3 class="text-lg font-semibold">Users</h3>
                        <div v-if="searchResult.users.length>0">
                            <UserListItem  v-for="(user, index) in searchResult.users" :key="index" :user="user" />
                        </div>

                        <div v-else class="text-center py-2 text-gray-400">
                            <p>No users were found</p>
                        </div>
                    </div>
                    <div v-if="searchResult.groups" class="bg-white p-3 max-h-[200px] overflow-auto shadow rounded-lg">
                        <h3 class="text-lg font-semibold">Groups</h3>
                        <div v-if="searchResult.groups.length>0">
                            <GroupItem  v-for="(group, index) in searchResult.groups" :key="index" :group="group" />
                        </div>
                        <div v-else class="text-center py-2 text-gray-400">
                            <p>No groups were found</p>
                        </div>
                    </div>
                </div> -->

                <slot />
                <SearchModal v-if="isSearchModalOpen"   v-model="isSearchModalOpen" @onSearchModelClose="isSearchModalOpen=false" />
            </main>
        </div>
    </div>
</template>

