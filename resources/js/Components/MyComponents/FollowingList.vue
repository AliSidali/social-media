<template>
    <div class="p-3   flex flex-col bg-white rounded border lg:h-full">
        <div class="block lg:hidden">
            <Disclosure v-slot="{open}">
                <DisclosureButton class="w-full flex justify-between items-center">
                     <h3 class=" text-xl font-bold"> My Friends </h3>
                     <div>
                        <ChevronDownIcon  class="w-6 transition-all" :class="open ? 'rotate-180 transform' : ''"/>
                     </div>
                </DisclosureButton>
                <DisclosurePanel class="h-[300px] flex flex-col  mt-3">
                    <FollowingListItem />
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="hidden h-full lg:flex lg:flex-col">
            <h3 class=" text-xl font-bold mb-3"> My Friends </h3>
            <FollowingListItem />
        </div>
    </div>
</template>
<script setup>
import FollowingListItem from '@/Components/MyComponents/FollowingListItem.vue';
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import { ChevronDownIcon, ChevronUpIcon  } from '@heroicons/vue/24/solid'


</script>
