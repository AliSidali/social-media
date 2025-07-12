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
            class="flex min-h-full items-center justify-center  text-center"
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
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white  text-start align-middle shadow-xl transition-all dark:bg-slate-800 dark:text-gray-100"
              >
                <DialogTitle
                  as="h3"
                  class="text-xl font-bold bg-gray-100 leading-6 text-gray-900 p-4 dark:bg-slate-900 dark:text-gray-100"
                >
                  {{ translations.invitation }}

                </DialogTitle>
                <div class="p-6">
                    <div class="mt-2">
                        <label for="" class="font-semibold">{{ translations.email }} / {{translations.username}}</label>
                        <TextInput v-model="form.email" class="w-full mt-2" :class="page.props.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''" />
                        <span class="text-red-600">{{ page.props.errors.email }}</span>
                    </div>

                    <div class="mt-4 flex justify-between">
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent text-blue-100 px-4 py-2 text-sm font-medium bg-blue-800 hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="closeModal"
                    >
                        {{ translations.cancel_button }}
                    </button>
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent text-blue-100 px-4 py-2 text-sm font-medium bg-blue-800 hover:bg-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="sendInvitation"
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
  import {  ref, watch } from 'vue'
  import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
  } from '@headlessui/vue'
import { useForm, usePage } from '@inertiajs/vue3';
import TextInput from '../TextInput.vue';


  const props = defineProps({
    modelValue: Boolean,
    group : Object,
  })
  const emit = defineEmits(['onCloseModal'])
  const isOpen = ref(props.modelValue);
  const page = usePage();
  const translations = page.props.translations;
  const form = useForm({
    email: ''
  })

  watch(()=>props.modelValue, ()=>{
    isOpen.value = props.modelValue
  })

  function closeModal() {
    form.reset();
    isOpen.value = false
    emit('onCloseModal', isOpen.value)
  }


  const sendInvitation = ()=>{
    form.post(route('group.inviteUser', props.group.slug), {
        onSuccess:(res)=>{
            closeModal();

        }
    })
  }

  </script>
