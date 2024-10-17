<template>
    <Disclosure v-slot="{open}">
        <div  v-if="!open" v-html="content.substring(0, 200)" />

        <DisclosurePanel>

            <div  v-html="content" />
        </DisclosurePanel>

        <div v-if="content.length > 200" class="flex justify-end">
            <DisclosureButton class="text-blue-500 hover:underline">
                {{  open ? 'Read  Less': 'Read More' }}
            </DisclosureButton>
        </div>
    </Disclosure>
</template>
<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';

const props = defineProps({
    content : String
});
</script>
