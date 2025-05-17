<template>
<div>
    <div class="mt-4">
        <label for="" class="font-semibold">{{ translations.group_name_field }}</label>
        <!-- <ckeditor :editor="editor" v-model="form.about" :config="editorConfig" ></ckeditor> -->

        <TextInput v-model="form.name" class="w-full mt-1" :class="validationErrors.name ?'border-red-500' : ''"/>
        <span v-if="validationErrors.name" class="text-red-600">{{ validationErrors.name[0]}}</span>
    </div>
    <div class="flex items-center mt-4 gap-2">
        <Checkbox v-model:checked="form.auto_approval" />
        <label for="auto_aproval">{{ translations.auto_approval }}</label>
    </div>
    <div class="mt-4">
        <label class="font-semibold">{{ translations.group_text }}</label>
        <!-- <Ckeditor :editor="editor" v-model="form.about" :config="editorConfig"></Ckeditor> -->
        <InputTextarea v-model="form.about" class="mt-1"/>
        <span v-if="validationErrors.about" class="text-red-600  ">{{ validationErrors.about[0]}}</span>

    </div>
</div>

</template>
<script setup>
import TextInput from '../TextInput.vue';
import InputTextarea from './InputTextarea.vue';
import Checkbox from '../Checkbox.vue';


const props = defineProps({
    validationErrors:Object,
    translations:Object,
    form:Object
})


</script>
