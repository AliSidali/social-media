<template>
    <div class=" p-3  bg-white rounded border h-full">

        <div class="block  lg:hidden">
            <Disclosure v-slot="{open}" >
                <DisclosureButton class="w-full flex justify-between items-center">
                    <h3 class=" text-xl font-bold" > My Groups </h3>
                    <ChevronDownIcon   class="w-6 transition-all" :class="open ? 'rotate-180 transform' : ''" />
                </DisclosureButton>
                <DisclosurePanel class="mt-3 h-[300px] flex flex-col">
                    <GroupListItem />
                </DisclosurePanel>
            </Disclosure>
        </div>


        <div class="hidden lg:flex lg:flex-col lg:h-full ">
            <h3 class=" text-xl font-bold mb-3"> My Groups </h3>
            <GroupListItem />
        </div>


    </div>

</template>
<script setup>
import GroupListItem from '@/Components/MyComponents/GroupListItem.vue';
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/solid'


</script>
