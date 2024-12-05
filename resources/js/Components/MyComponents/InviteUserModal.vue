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
                  Invite User

                </DialogTitle>
                <div class="mt-2">
                    <label for="" class="font-semibold">email or username</label>
                    <TextInput v-model="form.email" class="w-full mt-2" :class="page.props.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''" />
                    <span class="text-red-600">{{ page.props.errors.email }}</span>
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
                    @click="sendInvitation"
                  >
                    Submit
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
  const emit = defineEmits(['closeModal'])
  const isOpen = ref(props.modelValue);
  const page = usePage();
  const form = useForm({
    email: ''
  })

  watch(()=>props.modelValue, ()=>{
    isOpen.value = props.modelValue
  })

  function closeModal() {
    form.reset();
    isOpen.value = false
    emit('closeModal', isOpen.value)
  }


  const sendInvitation = ()=>{
    form.post(route('group.inviteUser', props.group.slug), {
        onSuccess:(res)=>{
            closeModal();

        }
    })
  }

  </script>
