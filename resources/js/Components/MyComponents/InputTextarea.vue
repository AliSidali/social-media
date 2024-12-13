<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    autoResize: {
        type: Boolean,
        default: false
    }
})

const model = defineModel({
    type: String,
<<<<<<< HEAD
=======
    required: true,
>>>>>>> 39623f4 (implement post creation)
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const onInputChange = ()=>{
    if(props.autoResize){
        input.value.style.height = input.value.scrollHeight +  'px';
    }
}
<<<<<<< HEAD

onMounted(()=>{
    input.value.style.height = input.value.scrollHeight +  'px';
})
=======
>>>>>>> 39623f4 (implement post creation)
</script>

<template>
    <textarea
<<<<<<< HEAD
        class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
=======
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
>>>>>>> 39623f4 (implement post creation)
        v-model="model"
        ref="input"
        @input="onInputChange"
    ></textarea>
</template>
