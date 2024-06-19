
<script setup>
import { computed, ref, watch } from 'vue'
import {TransitionRoot,TransitionChild,Dialog,DialogPanel, DialogTitle,} from '@headlessui/vue'
import InputTextarea from './InputTextarea.vue';
import PostUserHeader from './PostUserHeader.vue';
import { useForm } from '@inertiajs/vue3';
import { BookmarkIcon, PaperClipIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import BalloonEditor  from '@ckeditor/ckeditor5-build-balloon';
import { isImage } from '@/helpers';

const props = defineProps({
  modelValue: Boolean,
  post: {
    type: Object,
    required: true
  }
})

const resetForm = ()=>{
  PostForm.reset();
  attachments.value= [];
}

//toggle modal
const emit = defineEmits(['update:modelValue']);

const isOpen = computed({
  get: ()=>props.modelValue,
  set: (newValue)=>{
    emit('update:modelValue', newValue);
  }
})

function closeModal() {
  isOpen.value = false;
  resetForm();
}

//UPDATE OR CREATE POST

const PostForm = useForm({
  id: props.post.id,
  body: props.post.body,
  attachments: null,
})

watch(()=>props.post, ()=>{
  PostForm.body = props.post.body
})


const submit = ()=>{
  if(props.post.id){
          PostForm.attachments = attachments.value.map(index=>index.file);

          PostForm.put(route('post.update', props.post.id), {
          preserveScroll:true,
          onSuccess: ()=>{
            closeModal();
          }
      });
  }else{
      PostForm.attachments = attachments.value.map(index=>index.file);

      PostForm.post(route('post.store'), {

          preserveScroll:true,
          onSuccess: ()=>{
            closeModal();
          }
      })
  }

}


//CKEDITOR CONFIG
const editor = BalloonEditor;
const editorConfig= {
  toolbar: [ 'heading','|', 'link','|','bold', 'italic',  '|', 'bulletedList', 'numberedList','|', 'outdent','indent', '|', 'blockquote' ],

};


//DISPLAY ATTACHMENT ON MODAL
const attachments = ref([]);
const onAttachmentChoose = async(evt)=>{
 const files = evt.target.files;
 for(let file of files){
  let attachment = {
      file,
      url: await readFile(file)
  }

  attachments.value.push(attachment);
 }


}
const readFile = (file)=>{
      return new Promise((resolve, reject)=>{
          if(isImage(file)){
              const reader = new FileReader();
              reader.onload = ()=>{
                  resolve(reader.result);
              }
              reader.onerror = reject
              reader.readAsDataURL(file);
          }else{
              resolve(null);
          }
      })
 }

const removeAttachment = (attachment)=>{
  attachments.value = attachments.value.filter(file => file !== attachment);
}

</script>


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
                <!-- MODAL HEADER -->
                <DialogTitle
                  as="h3"
                  class=" font-medium p-3 px-4 bg-gray-200 text-gray-900 flex justify-between items-center"
                >
                  {{post.id ?' Update Post':'Create Post'}}
                  <button class="w-8 h-8 rounded-full flex justify-center items-center hover:bg-black/10 transition">
                      <XMarkIcon class="w-5 h-5" @click="closeModal" />
                  </button>
                </DialogTitle>

                <!-- MODAL BODY -->
                <div class="p-4 ">
                    <PostUserHeader :post="post" :showTime="false" class="mb-3"/>
                    <div class="border-2 border-gray-200 mb-3">
                        <ckeditor :editor="editor" v-model="PostForm.body" :config="editorConfig"></ckeditor>
                    </div>
                    <div class="grid  gap-2 my-3" :class="attachments.length == 1 ? 'grid-cols-1 ' : 'grid-cols-2 '">
                        <div  v-for="(attachment, index) in attachments" :key="index"  class="relative group">
                            <div v-if="isImage(attachment.file)"  class="h-52">
                                <img :src="attachment.url"   class="h-full w-full" alt="">
                            </div>
                            <div v-else  class="flex flex-col justify-center items-center text-gray-500 h-52 bg-blue-100">
                                <PaperClipIcon  class=" w-10 " />
                                <small class="font-semibold text-center">{{ attachment.file.name }}</small>
                            </div>
                            <button @click="removeAttachment(attachment)" class="absolute top-2 right-2 w-8 h-8 text-white rounded-full bg-black/30 hover:bg-black/40 flex justify-center items-center">
                                <XMarkIcon class="w-5 h-5" />
                            </button>
                          </div>

                    </div>
                </div>


                <!-- MODAL BOTTOM FOR BUTTONS -->
                <div class="p-4 flex gap-2">
                  <button
                    type="button"
                    class="relative flex items-center justify-center w-full rounded-md border border-transparent bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="submit"
                  >
                    <PaperClipIcon class="w-4 h-4 mr-2" />
                    Attach Files

                    <input @click.stop type="file" @change="onAttachmentChoose" class="absolute inset-0 opacity-0" multiple>
                  </button>
                  <button
                    type="button"
                    class="flex items-center justify-center  w-full rounded-md border border-transparent bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="submit"
                  >
                    <BookmarkIcon class="w-4 h-4 mr-2" />
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
