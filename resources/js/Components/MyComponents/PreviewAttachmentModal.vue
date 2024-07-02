<template>

    <TransitionRoot    appear :show="isOpen" as="template">
      <Dialog as="div" @close="closeModal" class=" z-10">
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
            class="h-full w-full"
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
                class="h-full w-full transform overflow-hidden  bg-white  text-left align-middle  transition-all"
              >
                <div
                    type="button"
                    class="absolute top-2 right-2 z-10  p-2 cursor-pointer  text-white hover:rounded-full  hover:bg-black/50"
                    @click="closeModal"
                  >
                    <XMarkIcon class="w-8 h-8" />
                </div>

                <div class="h-full w-full bg-slate-800">
                    <div  class="relative group h-full w-full">
                        <div @click="prev()" :class="{'cursor-not-allowed':currentIndex==0}" class=" absolute left-0 h-full w-20 flex items-center justify-center cursor-pointer bg-black/20 text-white opacity-0 group-hover:opacity-100" >
                            <ChevronLeftIcon class="w-16" />
                        </div>
                        <div @click="next()" :class="{'cursor-not-allowed':currentIndex == attachments.length-1}"  class="absolute  right-0 h-full w-20 flex items-center justify-center cursor-pointer bg-black/20 text-white opacity-0 group-hover:opacity-100" >
                            <ChevronRightIcon class="w-16" />
                        </div>
                        <div  v-if="isImage(currentAttachment)" class="h-full flex justify-center">
                            <img class="object-contain w-full h-full" :src="currentAttachment.url" alt="">
                        </div>
                        <div v-else class="flex flex-col justify-center items-center text-white h-full">
                            <PaperClipIcon  class=" w-10 " />
                            <small class="font-semibold">{{ currentAttachment.name }}</small>
                        </div>

                  </div>
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
import { isImage } from '@/helpers';
import {ChevronLeftIcon, ChevronRightIcon, XMarkIcon, PaperClipIcon } from '@heroicons/vue/24/solid';


  const props = defineProps({
    modelValue: Boolean,
    attachments: Array,
    index: Number
  })

  const emit = defineEmits(['update:modelValue']);



//open attachment modal
  const isOpen = ref(false);
  watch(()=>props.modelValue, ()=>{
    isOpen.value = props.modelValue
  })


//   DISPLAYING ATTACHMENTS MODAL

  function closeModal() {
    isOpen.value = false
    emit('update:modelValue', isOpen.value)
  }

    //1. DISPLAYING FIRST ATTACHMENT
    const currentIndex = ref(null);
    watch(()=>props.index, ()=>{
        currentIndex.value = props.index
    })
    const currentAttachment = computed(()=>{
        return props.attachments[currentIndex.value]
    });

    //2. MOVING THROUGH FILES
    const prev = ()=>{
      if(currentIndex.value > 0){
        currentIndex.value--;
      }
    }

    const next = ()=>{
      if(currentIndex.value < props.attachments.length-1){
        currentIndex.value++;
      }
    }


  </script>
