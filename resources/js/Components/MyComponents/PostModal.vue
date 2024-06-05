<template>
  <teleport to="body">
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
                class="w-full max-w-md transform overflow-hidden rounded bg-white text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle
                  as="h3"
                  class=" font-medium p-3 px-4 bg-gray-200 text-gray-900 flex justify-between items-center"
                >
                  Update Post
                  <button class="w-8 h-8 rounded-full flex justify-center items-center hover:bg-black/10 transition">
                      <XMarkIcon class="w-5 h-5" @click="closeModal" />
                  </button>
                </DialogTitle>
                <div class="p-4">
                    <PostUserHeader :post="post" :showTime="false" class="mb-3"/>
                    <InputTextarea v-model="updatePostForm.body" autoResize/>
                </div>

                <div class="p-4">
                  <button
                    type="button"
                    class="inline-flex justify-center w-full rounded-md border border-transparent bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="submit"
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
  </teleport>
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
  import InputTextarea from './InputTextarea.vue';
  import PostUserHeader from './PostUserHeader.vue';
  import { useForm } from '@inertiajs/vue3';
import { XMarkIcon } from '@heroicons/vue/24/solid';

  const props = defineProps({
    modelValue: Boolean,
    post: {
      type: Object,
     // required: true
    }
  })

  //toggle modal
  const emit = defineEmits(['update:modelValue']);

  const isOpen = computed({
    get: ()=>props.modelValue,
    set: (newValue)=>{
      emit('update:modelValue', newValue);
    }
  })

  function closeModal() {
    isOpen.value = false
  }

  //UPDATE POST

const updatePostForm = useForm({
    body: null
})

watch(()=>props.post, ()=>{
    updatePostForm.body = props.post.body
})


  const submit = ()=>{
    updatePostForm.put(route('post.update', props.post.id), {
        preserveScroll:true,
        onSuccess: ()=>{
            isOpen.value = false;
        }
    });
  }
  </script>
