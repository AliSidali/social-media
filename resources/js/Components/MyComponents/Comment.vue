<template>
<div>
    <div class=" flex w-full mb-4 relative gap-3">


        <img :src="comment.user.avatar_path" alt="" class=" z-10 w-8 h-8 rounded-full">
        <div v-if=" editedComment.id !== comment.id"  class="flex justify-between w-full ">
            <div>
                <div class="bg-gray-100 px-4 py-2 rounded-xl dark:bg-slate-900">
                    <h6 class="text-sm font-semibold">{{ comment.user.name }}{{ comment.id }}</h6>
                    <ReadMoreLess :content="comment.text"/>
                </div>

                <!-- display comment attachment -->
                <button v-if="comment.attachment" class="mt-1" @click="previewAttachment(null)">
                    <img :src="comment.attachment.url" class="max-w-40 max-h-40 rounded-lg" alt="">
                </button>

                <!-- comment buttons (reaction, reply, view subcomment button) -->
                <div class="flex gap-2 py-0.5">
                    <div class="text-xs text-gray-600 dark:text-gray-300">
                        {{ comment.updated_at }}
                    </div>
                    <button @click="sendReaction" :class="{'bg-indigo-100':comment.user_has_comment_reaction}" class="flex text-xs gap-1  px-1 rounded text-indigo-600 hover:bg-indigo-50" >
                        <HandThumbUpIcon  class="w-3"/>
                        <span class="mr-1">{{ comment.comment_reaction_num }}</span>
                        {{ translations.like_button }}
                    </button>
                    <button @click="showReplyBox" class="flex text-xs gap-1  px-1 rounded text-indigo-600 hover:bg-indigo-50">
                        <ChatBubbleLeftEllipsisIcon  class="w-3"/>
                        {{ translations.reply_button }}
                    </button>
                </div>
                <button v-if="comment.sub_comments.length>0 && !showSubcomments"  @click="showSubcomments=true" class="my-2">
                    {{ translations.subcomment_num_text }}  {{ comment.subcomment_num }}
                </button>


            </div>
            <EditDeleteDropdown  :comment="comment"  :post="post"     @edit="editComment" @delete="deleteComment" class="opacity-0 group-hover:opacity-100" />

        </div>

        <!-- update comment input -->
        <div v-else class="w-full">

            <div class="w-[60%] border border-gray-300 rounded-xl bg-gray-100 ">
                <!-- <ckeditor :editor="editor" v-model="editedComment.text" :config="editorConfig" class=" text-end px-4 py-2"></ckeditor> -->
              <TextInput v-model="editedComment.text" class="mt-1"/>

                <div class="flex justify-between px-5">
                    <div class=" text-white bg-black/50 left-1/3 top-1/4 rounded-full hover:bg-black/80">
                        <div v-if="!deletedAttachmentId" class=" relative p-1">
                            <input type="file"  @change="displayAttachment(comment.attachment, $event)" accept="image/*" class="absolute opacity-0 cursor-pointer w-5">
                            <CameraIcon class="w-4" />
                        </div>
                    </div>
                    <button  type="button" @click="updateComment" >
                        <PaperAirplaneIcon class="w-5" :class="page.props.lang==='ar' ? 'rotate-180' : ''" />
                    </button>

                </div>
            </div>
            <div  class="mt-2 relative w-20" v-if="editedComment.attachment">

                <button type="button" v-if="!deletedAttachmentId && editedComment.attachment.id" @click="deleteAttachment" class="absolute -top-2 -right-2 bg-black/60 text-white rounded-full">
                    <XMarkIcon class="  w-5 "  />
                </button>
                <img :src=" editedComment.attachment.url " class="w-20  rounded" :class="{'opacity-50 ':deletedAttachmentId && editedComment.attachment.id}" alt="">
            </div>

            <div class="flex justify-end">
                <button type="button" @click="cancelUpdate" class="text-blue-800 hover:text-white  hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    {{ translations.cancel_button }}
                </button>
            </div>
        </div>
        <!-- left border for subcomments connected to its parent -->
    </div>


    <!-- comment reply section -->
    <div v-if="hasReplyBox"  class="flex ml-8 mb-3">
        <img :src="user.avatar_path" alt="" class="w-8 h-8 rounded-full mr-3">
        <div class="w-full">
            <div class="w-[60%] border border-gray-300 rounded-xl bg-gray-100">
                <!-- <ckeditor :editor="editor" v-model="replyComment" :config="editorConfig" ></ckeditor> -->
                <TextInput v-model="replyComment" class="mt-1"/>
                <div class="flex justify-between px-5">
                    <div class="relative p-2 rounded-full hover:bg-gray-200">
                        <input type="file" @change="displayNewAttachment" accept="image/*" class="absolute opacity-0 cursor-pointer w-5">
                        <CameraIcon class="w-4" />
                    </div>
                    <button type="button" @click="sendComment" >
                        <PaperAirplaneIcon class="w-5"/>
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


    <!-- recursive comments -->
    <div   v-if="comment.sub_comments.length>0 && showSubcomments">
        <!-- display replies -->
        <div v-for="(subcomment, index) in comment.sub_comments" :key="index">
            <comment   :comment="subcomment" class="ml-8" :post="post" @onCreateComment="sendComment" @onPreviewAttachment="previewAttachment"/>
        </div>
    </div>

</div>
</template>
<script setup>
import ReadMoreLess from './ReadMoreLess.vue';
import Comment from './Comment.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/vue3';
import {CameraIcon, ChatBubbleLeftEllipsisIcon,  HandThumbUpIcon, PaperAirplaneIcon, XMarkIcon} from '@heroicons/vue/24/solid';
import EditDeleteDropdown from './EditDeleteDropdown.vue';
import { ref, watch } from 'vue';
import axiosClient from '@/axiosClient';
import {helpers} from '@/helpers';


const {readFile} = helpers();
const page = usePage();
const user = page.props.auth.user;
const translations = page.props.translations;
const props = defineProps({
    comment: Object,
    post: Object
})


const emit = defineEmits(['onCreateComment', 'onPreviewAttachment']);

// for editing comment
const  editedComment = ref({
        id:null,
      text:null,
      attachment: null
    });
const deletedAttachmentId = ref(null);

// reply to a comment
const hasReplyBox = ref(false);
const showSubcomments = ref(false);
const replyComment = ref('');

//display comment attachment
const attachment = ref(null);



// edit comment and cancel editing


const editComment = ()=>{
    editedComment.value = {
      id: props.comment.id,
      text: props.comment.text,
      attachment: props.comment.attachment
    };
}

const cancelUpdate = ()=>{
    editedComment.value = {};
    deletedAttachmentId.value=null;

}

const updateComment= ()=>{

  axiosClient.post(route('comment.update', editedComment.value.id), {
   text: editedComment.value.text,
   deletedAttachmentId:deletedAttachmentId.value,
   attachment: editedComment.value.attachment ? editedComment.value.attachment.file : null
  }
  ,{
        headers: {
            'Content-Type': 'multipart/form-data',
        }
    }
    ).then(({data})=>{
        props.comment.text = data.comment.text;
        props.comment.attachment = data.comment.attachment;
        cancelUpdate();


  });
}

const displayAttachment = async(hasDeletedAttachment, e)=>{
    if(hasDeletedAttachment){

        deleteAttachment();
     }
    const file = e.target.files[0];
    editedComment.value.attachment = {
        file:file,
        url: await readFile(file)
    }

}


// send reactions (like, love)

const sendReaction = ()=>{
    axiosClient.post(route('comment.reaction', props.comment.id), {
        reaction:'like'
    }).then(({data})=>{
            props.comment.user_has_comment_reaction = data.hasUserReaction;
            props.comment.comment_reaction_num = data.commentReactionNum;
    })
}

// delete comment
const deleteComment = ()=>{
  if (!window.confirm(translations.delete_comment_confirmation)) {
    return false;
  }

    axiosClient.delete(route('comment.destroy', props.comment.id))
    .then(({data})=>{
        props.post.comments = data.post.comments;
       removeElement(props.post.comments);

        props.post.post_comments_num = data.post.post_comments_num;


    });
}

const removeElement = (comments)=>{
    comments.forEach((c, i)=>{
        if(c.id === props.comment.id){
            comments.splice(i, 1);
        }
        removeElement(c.sub_comments);
    })

}

//this section for reply comment

const showReplyBox = ()=>{
    hasReplyBox.value = !hasReplyBox.value;
    showSubcomments.value = true;
}



const sendComment = ()=>{

    axiosClient.post(route('post.comment', props.post.id), {
            text: replyComment.value,
            parent_id: props.comment.id,
            attachment: attachment.value ? attachment.value.file : null
        },
        {
            headers: {
            'Content-Type': 'multipart/form-data',
        }
        }).then(({data})=>{
            props.comment.sub_comments.unshift(data.comment);
            props.post.post_comments_num++;
            replyComment.value = "";
            attachment.value=null;
            hasReplyBox.value = false;

        })
}

// display comment attachment before sending it and assign file to variable
const displayNewAttachment = async(e)=>{

    const file = e.target.files[0];
    attachment.value = {
        file:file,
        url: await readFile(file)
    }

}


const cancelAttachment = ()=>{
    attachment.value = null;
}

//display full screen attachment
const previewAttachment = (attachment)=>{
    let currentAttachment= attachment ? attachment : props.comment.attachment;
    emit('onPreviewAttachment', currentAttachment, 0)
}

const deleteAttachment = ()=>{
    if (props.comment.attachment) {
        deletedAttachmentId.value = props.comment.attachment.id;
    }
}



</script>
