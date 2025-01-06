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
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white  align-middle shadow-xl transition-all text-start"
              >
                <DialogTitle
                  as="h3"
                  class="text-lg font-semibold  leading-6 text-gray-900 border-b-2 p-4 bg-gray-100 flex items-center justify-between"
                >
                  <h3>{{ translations.group_modal_header }}</h3>
                  <XMarkIcon @click="closeModal" class="w-8 rounded-full cursor-pointer p-1 hover:bg-gray-200"/>
                </DialogTitle>
                <div class="p-6">
                    <div class="mt-4">
                        <label for="" class="font-semibold">{{ translations.group_name_field }}</label>
                        <TextInput v-model="form.name" class="w-full mt-1" :class="validationErrors.name ?'border-red-500' : ''"/>
                        <span v-if="validationErrors.name" class="text-red-600">{{ validationErrors.name[0]}}</span>
                    </div>
                    <div class="flex items-center mt-4 gap-2">
                        <Checkbox v-model:checked="form.auto_approval" />
                        <label for="auto_aproval">{{ translations.auto_approval }}</label>
                    </div>
                    <div class="mt-4">
                        <label class="font-semibold">{{ translations.group_text }}</label>
                        <InputTextarea v-model="form.about" class="mt-1"/>
                        <span v-if="validationErrors.about" class="text-red-600  ">{{ validationErrors.about[0]}}</span>

                    </div>

                    <div class="mt-4 flex justify-between">
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="closeModal"
                    >
                        {{ translations.cancel_button }}
                    </button>
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="createGroup"
                    >
                        {{ translations.send_button }}
                    </button>
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
import { useForm, usePage } from '@inertiajs/vue3';
import TextInput from '../TextInput.vue';
import InputTextarea from './InputTextarea.vue';
import Checkbox from '../Checkbox.vue';
import axiosClient from '@/axiosClient';
import { XMarkIcon } from '@heroicons/vue/24/solid';


  const props = defineProps({
    modelValue: Boolean
  })

  const page = usePage().props;
  const translations = page.translations;
  const validationErrors = ref({
    name : null,
    about : null
  });

  const emit = defineEmits(['closeGroupModal', 'addGroup'])
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
    isOpen.value = false;
    validationErrors.value = {
        name:null,
        about:null
    };
    emit('closeGroupModal', isOpen.value)
  }


  const createGroup = ()=>{

    axiosClient.post(route('group.store'),form)
            .then(({data})=>{
                closeModal();
                emit('addGroup', data.group);
            }).catch(({response})=>{
                validationErrors.value = response.data.errors;
                console.log(validationErrors.value);

                console.log(response);


            })
  }

  </script>
