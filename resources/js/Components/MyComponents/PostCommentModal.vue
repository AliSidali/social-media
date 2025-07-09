<template>
    <TransitionRoot appear :show="isCommentModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10 ">
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
            class="flex h-full items-center justify-center p-4 text-center"
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
                class="flex flex-col w-[60%] max-h-full transform  rounded-2xl bg-white py-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle
                  as="h3"
                  class="text-lg font-medium leading-6 text-gray-900 flex justify-between items-center  shadow-xl px-6 text-start"
                >
                  <PostUserHeader :post="post"/>
                  <div class="p-2  cursor-pointer rounded-full hover:bg-gray-100" @click="closeModal">
                      <XMarkIcon class="w-5" />
                  </div>

                </DialogTitle>

                <div class="my-2 h-full overflow-auto text-start">

                    <p  v-html="post.body "  class="p-6"/>
                    <div class="grid  gap-1 bg-gray-900 " :class="post.attachments.length>1 ? 'grid-cols-2 ' : 'grid-cols-1' ">
                        <div @click="previewAttachment(null, index)" class="relative cursor-pointer" v-for="(attachment, index) in post.attachments.slice(0,4)" :key="index">
                            <div class="absolute inset-0 bg-black/50 text-white flex justify-center items-center text-xl" v-if="index==3 && post.attachments.length > 4">
                                + {{ post.attachments.length -4 }} more
                            </div>
                            <img :src="attachment.url" alt="" class="w-full h-full ">
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-b px-4 py-2">
                        <div class="flex  gap-1  mb-2 ">
                            <div v-if="hasLikeReaction">
                                <HandThumbUpIcon  class="w-6 text-white rounded-full bg-blue-600 p-1"/>
                            </div>
                            <HeartIcon v-if="hasLoveReaction" class="w-6 text-white rounded-full bg-red-600 p-1"/>
                            <span class="ml-2">{{ post.reactions.length }}</span>
                        </div>
                        <div class="flex gap-1">
                            <span>{{ post.post_comments_num }}</span>
                            <ChatBubbleOvalLeftIcon class="w-5" />
                        </div>
                    </div>

                    <!-- diplaying post comments and update input -->
                    <div class="py-3 px-6">
                        <div v-for="(comment, index) in post.comments" :key="index"  class="mb-4 group">
                            <Comment  :comment="comment"  :post="post" @onCreateComment="createComment" @onPreviewAttachment="previewAttachment"/>

                        </div>


                    </div>
                </div>
                <!-- creating comment input field -->


                <div  class="flex px-6 gap-3 mb-3 w-full">
                    <img :src="user.avatar_path" alt="" class="w-10 h-10 rounded-full">

                    <div class="w-full ">
                        <div class="border border-gray-300 rounded-xl bg-gray-100">
                            <!-- <ckeditor :editor="editor" v-model="commentText" :config="editorConfig" ></ckeditor> -->
                           <TextInput v-model="commentText" class="mt-1"/>

                            <div class="flex justify-between px-5">
                                <div class="relative p-2 rounded-full hover:bg-gray-200">
                                    <input type="file" @change="displayAttachment" accept="image/*" class="absolute opacity-0 cursor-pointer w-5">
                                    <CameraIcon class="w-4" />
                                </div>

                                <button type="button" @click="createComment" >
                                    <PaperAirplaneIcon class="w-5 "  :class="page.props.lang==='ar'?'rotate-180' : ''" />
                                </button>
                            </div>
                        </div>
                        <div v-if="attachment" class="mt-2 relative w-20">
                            <button type="button" @click="cancelAttachment" class="absolute -top-2 -right-2 bg-black/60 text-white rounded-full">
                                <XMarkIcon class="  w-5 "  />
                            </button>
                            <img :src="attachment.url" class=" w-20 rounded" alt="">
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
  import { computed, onMounted, ref, watch } from 'vue';
  import { MenuItem } from '@headlessui/vue'
  import {ChatBubbleOvalLeftIcon,ChatBubbleLeftRightIcon} from '@heroicons/vue/24/outline';
  import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
  } from '@headlessui/vue'
    import { XMarkIcon, HandThumbUpIcon, HeartIcon, PaperAirplaneIcon,CameraIcon  } from '@heroicons/vue/24/solid';
    import PostUserHeader from './PostUserHeader.vue';
    import Comment from './Comment.vue';
    import { router, useForm, usePage } from '@inertiajs/vue3';
    import axiosClient from '@/axiosClient';
    import {helpers} from '@/helpers';
    import TextInput from '@/Components/TextInput.vue'
    const props = defineProps({
        modelValue : Boolean,
        post: Object
    })

    const {readFile} = helpers();

    const page = usePage();
    const user = page.props.auth.user;
    const translations = page.props.translations;

    const commentText = ref('');


    const emit = defineEmits(['update:modelValue', 'onAttachmentClick']);

    //toggle comment table
    const isCommentModalOpen = ref(false);

    watch(()=>props.modelValue, ()=>{
        isCommentModalOpen.value = props.modelValue
    })

    function closeModal() {
        isCommentModalOpen.value = false;
        emit('update:modelValue', isCommentModalOpen.value);
    }


    //display preview attachment modal
    const previewAttachment = (attachment, index)=>{
        let attachments = [];
        if(attachment){
            attachments.push(attachment);
        }else{
            attachments = props.post.attachments;
        }
        emit('onAttachmentClick', attachments, index);
        closeModal();
    }

    //show reaction icons
    const hasLikeReaction = ref(false);
    const hasLoveReaction = ref(false);
    watch(()=>props.post,()=>{
        hasLikeReaction.value = reactionIteration('like');
        hasLoveReaction.value = reactionIteration('love');
    })

    const reactionIteration = (react)=>{
        for(let reaction of props.post.reactions){
            if(reaction.type == react.toLowerCase()){
                return true;
            }
        }
    }

 //send comment


    //2.create new comment
    const createComment = ()=>{

        axiosClient.post(route('post.comment', props.post.id), {
            text: commentText.value,
            attachment: attachment.value ? attachment.value.file : null,
        },{
            headers: {
                'content-type':'multipart/form-data'
            }
        }

        ).then(({data})=>{
            console.log(data);

        // if(!data.comment.parent_id){
        //     props.post.comments.unshift(data.comment);
        // }else{
        //     props.post.comments.forEach(c=>{
        //         if(c.id == data.comment.parent_id && !c.parent_id){
        //             c.sub_comments.unshift(data.comment);
        //         }else{
        //             console.log(data.comment);

        //             addSubcomment(c.sub_comments, data.comment);
        //         }
        //     })
        // }
        props.post.comments.unshift(data.comment);
        props.post.post_comments_num = data.post_comments_num;
        commentText.value = '';
        cancelAttachment();

        });
    }

    const addSubcomment = (subcomments, comment)=>{
        console.log(comment);

        return subcomments.forEach((sc)=>{
                if(sc.id === comment.parent_id){
                    sc.sub_comments.push(comment);
                }else{
                    addSubcomment(sc.sub_comments, comment);
                }


         });

    }

    const attachment = ref(null);
    const displayAttachment = async(e)=>{
        let file = e.target.files[0];
        attachment.value = {
            file,
            url: await readFile(file)
        }
    }

    const cancelAttachment = ()=>{
        attachment.value= null;
    }










</script>
