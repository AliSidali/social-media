<template>

  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
                Payment successful
              </DialogTitle>
              <div class="mt-2">
                <span @click="prev">previous</span>
                <img :src="dispalyedAttachment.url" alt="">
                <span @click="next">next</span>

            </div>

              <div class="mt-4">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="closeModal"
                >
                  Got it, thanks!
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'

const props = defineProps({
    modelValue:Boolean,
    attachments:Object,
    index:Number
})
const emit = defineEmits(['onClosePhotoModal'])

const isOpen = ref(false)
const currentIndex = ref(0);
function closeModal() {
  isOpen.value = false;
  emit('onClosePhotoModal', isOpen.value)
}

// const openModal = computed(()=>{
//     isOpen.value=props.modelValue
// })

watch(()=>props.modelValue, ()=>{
    isOpen.value=props.modelValue
})

watch(()=>props.index, ()=>{
    currentIndex.value = props.index;
})
const dispalyedAttachment = computed(()=>{

     return props.attachments[currentIndex.value];
})

const prev = ()=>{
    if(currentIndex.value>0){
        currentIndex.value--;
    }
}

const next = ()=>{
    if(currentIndex.value < props.attachments.length-1){
        currentIndex.value++;
    }

}

</script>
