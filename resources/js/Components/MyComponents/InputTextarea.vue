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

onMounted(()=>{
    input.value.style.height = input.value.scrollHeight +  'px';
})
</script>

<template>
    <textarea
        class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"

        v-model="model"
        ref="input"
        @input="onInputChange"
    ></textarea>
</template>
