
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
                class="w-full max-w-md transform overflow-hidden rounded bg-white text-start align-middle shadow-xl transition-all"
              >
                <DialogTitle
                  as="h3"
                  class=" font-medium p-3 px-4 bg-gray-200 text-gray-900 flex justify-between items-center"
                >
                  {{post.id ? translations.update_post_modal_header: translations.create_post_modal_header}}
                  <button class="w-8 h-8 rounded-full flex justify-center items-center hover:bg-black/10 transition">
                      <XMarkIcon class="w-5 h-5" @click="closeModal" />
                  </button>
                </DialogTitle>

                <!-- MODAL BODY -->
                <div class="p-4 ">
                    <PostUserHeader :post="post" :showTime="false" class="mb-3"/>
                    <div v-if="showExtensionMessage" class="bg-sky-200 px-4 py-2 text-gray-700  border-sky-600 border-s-4">
                      <span>{{ translations.file_type_alert }}</span> <br/>
                      <small>{{ attachmentExtensions.join(', ') }}</small>
                    </div>

                    <div>
                        <div class=" border border-gray-200 mt-3" :class="{'border-red-500' : errors.body}">
                            <ckeditor :editor="editor" v-model="postForm.body" :config="editorConfig" ></ckeditor>
                        </div>
                        <span class="text-red-500" v-if="errors.body">{{ errors.body}}</span>
                    </div>
                    <div>

                    </div>

                    <div class="grid  gap-2 my-3" :class="computedAttachments.length == 1 ? 'grid-cols-1 ' : 'grid-cols-2 '">
                            <div  v-for="(attachment, index) in computedAttachments" :key="index"  >
                                <div class="relative group border-2" :class="{'border-red-500' : errors.attachmentErrors[index]}">
                                    <div v-if="isImage(attachment.file||attachment)"  class="h-52 "  :class="{'opacity-50': attachment.deleted}">
                                        <img :src="attachment.url"   class="h-full w-full" alt="">
                                    </div>
                                    <div v-else class="flex flex-col justify-center items-center text-gray-500 h-52 bg-blue-100 " :class="{'opacity-50': attachment.deleted}">
                                        <PaperClipIcon  class=" w-10 " />
                                        <small class="font-semibold text-center">{{ (attachment.file||attachment).name }}</small>
                                    </div>
                                    <div class="absolute flex justify-between items-center  bg-black text-white py-2 px-4 font-semibold text-sm w-full bottom-0" v-if="attachment.deleted">
                                        {{ translations.attachment_delete_alert }}
                                        <ArrowUturnLeftIcon class="w-4 h-5 cursor-pointer" @click="undoAttachmentRemove(attachment)"/>
                                    </div>
                                    <button @click="removeAttachment(attachment)" class="absolute top-2 right-2 w-8 h-8 text-white rounded-full bg-black/30 hover:bg-black/40 flex justify-center items-center">
                                        <XMarkIcon class="w-5 h-5" />
                                    </button>
                                </div>
                                <small class="text-red-500">{{ errors.attachmentErrors[index] }}</small>
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
                    {{ translations.attach_files_button }}

                    <input @click.stop type="file" @change="onAttachmentChoose" class="absolute inset-0 opacity-0" multiple>
                  </button>
                  <button
                    type="button"
                    class="flex items-center justify-center  w-full rounded-md border border-transparent bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="submit"
                  >
                    <BookmarkIcon class="w-4 h-4 mr-2" />
                    {{ translations.send_button }}
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
import {TransitionRoot,TransitionChild,Dialog,DialogPanel, DialogTitle,} from '@headlessui/vue'
import InputTextarea from './InputTextarea.vue';
import PostUserHeader from './PostUserHeader.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { BookmarkIcon, PaperClipIcon, XMarkIcon, ArrowUturnLeftIcon } from '@heroicons/vue/24/solid';
import BalloonEditor  from '@ckeditor/ckeditor5-build-balloon';
import { helpers } from '@/helpers';


const {isImage} = helpers();

const props = defineProps({
  modelValue: Boolean,
  post: {
    type: Object,
    default:()=>({
        body:'',
        user:usePage().props.auth.user
    })
  },
  group_id:{
    type:Number,
    default:null
  }
})
const postForm = useForm({
  body: props.post.body,
  group_id:null,
  attachments: null,
  deleted_file_ids : [],
  _method: 'POST'
});

const page = usePage().props;
const translations = page.translations;

const resetForm = ()=>{
  postForm.reset();
  attachments.value= [];
}

const showExtensionMessage = ref(false);

const attachments = ref([]);

const attachmentExtensions = page.extensions;

const errors = ref({
    attachmentErrors: [],
    body:null,

});

//toggle modal
const emit = defineEmits(['update:modelValue', 'hideModal']);

const isOpen = computed({
  get: ()=>props.modelValue,
  set: (newValue)=>{
    emit('update:modelValue', newValue);
  }
})

const closeModal = () => {

  isOpen.value = false;
  emit('hideModal');
  resetForm();
  errors.value = {
    attachmentErrors: [],
    body: null
  };
  showExtensionMessage.value = false;
}

//UPDATE OR CREATE POST



//1. this watch for update
watch(()=>props.post, ()=>{
  postForm.body = props.post.body;
})


const submit = ()=>{

    postForm.attachments = attachments.value.map(index=>index.file);


  if(props.post.id){
        postForm._method = 'PUT';

        postForm.post(route('post.update', props.post.id), {
        preserveScroll:true,
        onSuccess: ()=>{
            closeModal();
        },
        onError: (errorMessages)=>{
          errors.value.body = errorMessages.body;
          setAttachmentErrors(errorMessages);
        }
    });
  }else{

      postForm.group_id = props.group_id;

      postForm.post(route('post.store'), {

          preserveScroll:true,
          onSuccess: ()=>{
            closeModal();
          },
          onError: (errorMessages)=>{
            errors.value.body = errorMessages.body;
            setAttachmentErrors(errorMessages);
        }
      })
  }

}


const setAttachmentErrors = (errs)=>{

  for(let key in errs){
      if(key.includes('.')){
          const [, index] = key.split('.');
          errors.value.attachmentErrors[index] = errs[key];
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

