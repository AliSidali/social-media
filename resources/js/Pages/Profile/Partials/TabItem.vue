<template>
    <button
        :class="[
            'px-4 py-2 outline-none',
            selected
            ? 'border-b-2 border-blue-500 text-blue-500'
            : 'text-gray-800',
        ]"
        >
        {{ text }}
    </button>
</template>
<script setup>
defineProps({
    selected: Boolean,
    text: String
})
</script>
