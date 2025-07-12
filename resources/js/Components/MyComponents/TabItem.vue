<template>
    <div class="relative">
        <button
            :class="[
                'px-4 py-2 outline-none capitalize ',
                selected ? 'border-b-2 border-blue-500 text-blue-500'
                : 'text-gray-800 dark:text-gray-100',
            ]"
            >
            {{ text }}
        </button>
        <span v-if="alert" class="absolute right-0.5 -top-1 bg-red-600 text-xs text-white py-0.5 px-2 rounded-full">{{ alert }}</span>
    </div>
</template>
<script setup>
defineProps({
    selected: Boolean,
    text: String,
    alert: Number
})
</script>
