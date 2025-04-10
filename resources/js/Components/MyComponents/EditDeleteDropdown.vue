<template>
    <Menu as="div" class="relative inline-block ">
                <div v-if="isOwner || isAdmin">
                    <MenuButton
                    class="w-8 h-8 rounded-full  flex justify-center items-center hover:bg-black/5 transition"
                    >
                    <EllipsisVerticalIcon class="w-5 h-5" />
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                    class="absolute z-10 end-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                    >
                    <div class="px-1 py-1 ">
                        <MenuItem v-slot="{ active }" v-if="isOwner">
                            <button
                                :class="[
                                active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                                @click="$emit('edit')"
                            >
                                <PencilIcon class="w-5 h-5 mr-2" />
                                {{ translations.edit_button }}
                            </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }"  v-if="isOwner || isAdmin" >
                            <button
                                :class="[
                                active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"

                                @click="$emit('delete', post.group.id)"
                            >
                                <TrashIcon class="w-5 h-5 mr-2" />
                                {{ translations.delete_button }}
                            </button>
                        </MenuItem>

                    </div>
                    </MenuItems>
                </transition>
            </Menu>
</template>
<script setup>
import {EllipsisVerticalIcon, TrashIcon, PencilIcon} from '@heroicons/vue/24/solid'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    post:{
        type:Object,
        default:null
    },
    comment:{
        type:Object,
        default:null
    }
})

defineEmits(['edit', 'delete']);
const page = usePage().props;
const currentUser = page.auth.user;
const translations = page.translations;

const isAdmin = computed(()=>{
    return props.post.group?.role === 'admin';
})

const isOwner = computed(()=>{
    return (!props.comment && props.post.user.id===currentUser.id) || props.comment?.user.id === currentUser.id;
})



</script>
