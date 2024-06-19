<div class="grid grid-cols-2  gap-2 my-3">
    <div  v-for="(attachment, index) in attachments.slice(0,4)" :key="index"  class="relative group">
        <div v-if="index==3 && attachments.length>4" class="absolute inset-0 bg-black/60 text-white  flex justify-center items-center" >
          <h3 class="text-2xl">
              + {{ attachments.length - 4 }} more
            </h3>
        </div>
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
