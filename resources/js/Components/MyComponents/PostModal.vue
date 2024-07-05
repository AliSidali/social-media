
<script setup>
import { computed, ref, watch } from 'vue'
import {TransitionRoot,TransitionChild,Dialog,DialogPanel, DialogTitle,} from '@headlessui/vue'
import InputTextarea from './InputTextarea.vue';
import PostUserHeader from './PostUserHeader.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { BookmarkIcon, PaperClipIcon, XMarkIcon, ArrowUturnLeftIcon } from '@heroicons/vue/24/solid';
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
  postForm.reset();
  attachments.value= [];
}

const attachments = ref([]);

const attachmentErrors = ref([]);

const attachmentExtensions = usePage().props.extensions;

//toggle modal
const emit = defineEmits(['update:modelValue', 'hideModal']);

const isOpen = computed({
  get: ()=>props.modelValue,
  set: (newValue)=>{
    emit('update:modelValue', newValue);
  }
})

function closeModal() {
  isOpen.value = false;
  emit('hideModal');
  resetForm();
  attachmentErrors.value = [];
  showExtensionMessage.value = false;
}

//UPDATE OR CREATE POST

const postForm = useForm({
  body: props.post.body,
  attachments: null,
  deleted_file_ids : [],
  _method: 'POST'
})

//1. this watch for update
watch(()=>props.post, ()=>{
  postForm.body = props.post.body;
})


const submit = ()=>{
  if(props.post.id){
        postForm.attachments = attachments.value.map(index=>index.file);
        console.log(
            postForm.attachments
        );
        postForm._method = 'PUT';

        postForm.post(route('post.update', props.post.id), {
        preserveScroll:true,
        onSuccess: ()=>{
            closeModal();
        },
        onError: (errors)=>{
          setAttachmentErrors(errors);
        }
    });
  }else{
      postForm.attachments = attachments.value.map(index=>index.file);

      postForm.post(route('post.store'), {

          preserveScroll:true,
          onSuccess: ()=>{
            closeModal();
          },
          onError: (errors)=>{
            setAttachmentErrors(errors);
        }
      })
  }

}

const showExtensionMessage = ref(false);

const setAttachmentErrors = (errors)=>{
  for(let key in errors){
      if(key.includes('.')){
          const [, index] = key.split('.');
          attachmentErrors.value[index] = errors[key];
        }
  }
}


//CKEDITOR CONFIG
const editor = BalloonEditor;
const editorConfig= {
  toolbar: [ 'heading','|', 'link','|','bold', 'italic',  '|', 'bulletedList', 'numberedList','|', 'outdent','indent', '|', 'blockquote' ],

};


//DISPLAY ATTACHMENT ON MODAL

// 1. display frontend attachment
const onAttachmentChoose = async(evt)=>{
 const files = evt.target.files;
 for(let file of files){
    let attachment = {
        file,
        url: await readFile(file)
    }

    attachments.value.push(attachment);

    //2. show extensions message after choosing none existing file extension
    const fileExtension = file.name.split('.').pop().toLowerCase();
    if(!attachmentExtensions.includes(fileExtension)){
        showExtensionMessage.value = true;
    }

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

 //2.  DISPLAYING BOTH BACKEND AND FRONTEND ATTACHMENTS
const computedAttachments = computed(()=>{
    return [ ...attachments.value, ...(props.post.attachments||[])];
})



 // REMOVE ATTACHMENT
const removeAttachment = (attachment)=>{
    if(attachment.file){
        attachments.value = attachments.value.filter(file => file !== attachment);
    }else if(!postForm.deleted_file_ids.includes(attachment.id)){
        attachment.deleted = true;
        postForm.deleted_file_ids.push(attachment.id);
    }
}

const undoAttachmentRemove = (attachment)=>{
    attachment.deleted = false
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
                        <ckeditor :editor="editor" v-model="postForm.body" :config="editorConfig"></ckeditor>
                    </div>
                    <div v-if="showExtensionMessage" class="bg-sky-200 px-4 py-2 text-gray-700 border-l-4 border-sky-600">
                      your files must be within the following extensions <br/>
                      <small>{{ attachmentExtensions.join(', ') }}</small>
                    </div>

                    <div class="grid  gap-2 my-3" :class="computedAttachments.length == 1 ? 'grid-cols-1 ' : 'grid-cols-2 '">
                            <div  v-for="(attachment, index) in computedAttachments" :key="index"  >
                                <div class="relative group border-2" :class="{'border-red-500' : attachmentErrors[index]}">
                                    <div v-if="isImage(attachment.file||attachment)"  class="h-52 "  :class="{'opacity-50': attachment.deleted}">
                                        <img :src="attachment.url"   class="h-full w-full" alt="">
                                    </div>
                                    <div v-else class="flex flex-col justify-center items-center text-gray-500 h-52 bg-blue-100 " :class="{'opacity-50': attachment.deleted}">
                                        <PaperClipIcon  class=" w-10 " />
                                        <small class="font-semibold text-center">{{ (attachment.file||attachment).name }}</small>
                                    </div>
                                    <div class="absolute flex justify-between items-center  bg-black text-white py-2 px-4 font-semibold text-sm w-full bottom-0" v-if="attachment.deleted">
                                        to be deleted
                                        <ArrowUturnLeftIcon class="w-4 h-5 cursor-pointer" @click="undoAttachmentRemove(attachment)"/>
                                    </div>
                                    <button @click="removeAttachment(attachment)" class="absolute top-2 right-2 w-8 h-8 text-white rounded-full bg-black/30 hover:bg-black/40 flex justify-center items-center">
                                        <XMarkIcon class="w-5 h-5" />
                                    </button>
                                </div>
                                <small class="text-red-500">{{ attachmentErrors[index] }}</small>
                            </div>

                    </div>
                </div>


                <!-- MODAL BOTTOM FOR BUTTONS -->
                <div class="p-4 flex gap-2">
                  <button
                    type="button"
                    class="relative flex items-center justify-center w-full rounded-md border border-transparent bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
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
