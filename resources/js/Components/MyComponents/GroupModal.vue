<template>
{{ isOpen }}
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
                  Create a new group
                </DialogTitle>
                <div class="mt-2">
                    <label for="" class="font-semibold">Group Name</label>
                    <TextInput v-model="form.name" class="w-full"/>
                </div>
                <div class="mt-2">
                    <Checkbox v-model:checked="form.auto_approval" class="mr-2"/>
                    <label for="auto_aproval">Enable auto approval</label>
                </div>
                <div class="mt-2">
                    <label class="font-semibold">Text</label>
                    <InputTextarea v-model="form.about" />
                </div>

                <div class="mt-4 flex justify-between">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModal"
                  >
                    cancel
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="createGroup"
                  >
                    Send
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
import { useForm } from '@inertiajs/vue3';
import TextInput from '../TextInput.vue';
import InputTextarea from './InputTextarea.vue';
import Checkbox from '../Checkbox.vue';
import axiosClient from '@/axiosClient';


  const props = defineProps({
    modelValue: Boolean
  })
  const emit = defineEmits(['closeGroupModal'])
  const isOpen = ref(props.modelValue);
  const form = useForm({
    name: "",
    about: "",
    auto_approval: true
  })

  watch(()=>props.modelValue, ()=>{
    isOpen.value = props.modelValue
  })

  function closeModal() {
    form.reset();
    isOpen.value = false
    emit('closeGroupModal', isOpen.value)
  }


  const createGroup = ()=>{

    axiosClient.post(route('group.store'),form)
            .then((res)=>{
                closeModal();
            })
  }

  </script>
