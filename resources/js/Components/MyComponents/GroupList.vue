<template>
    <div class=" p-3  bg-white rounded border h-full dark:bg-slate-900 dark:text-gray-100 dark:border-slate-900">
        <div class="block  lg:hidden">
            <Disclosure v-slot="{open}" >
                <DisclosureButton class="w-full flex justify-between items-center">
                    <h3 class=" text-xl font-bold" > {{ translations.group_title }}</h3>
                    <ChevronDownIcon   class="w-6 transition-all" :class="open ? 'rotate-180 transform' : ''" />
                </DisclosureButton>
                <DisclosurePanel class="mt-3 h-[300px] flex flex-col">
                    <GroupListItem :groups="groups"/>
                </DisclosurePanel>
            </Disclosure>
        </div>


        <div class="hidden lg:flex lg:flex-col h-[85%] ">
            <h3 class=" text-xl font-bold mb-3">{{ translations.group_title }} </h3>
            <GroupListItem :groups="groups"/>
        </div>

    </div>

</template>
<script setup>
import GroupListItem from '@/Components/MyComponents/GroupListItem.vue';
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/solid'
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    groups: Array
})

const page = usePage();
const translations = page.props.translations;
</script>
