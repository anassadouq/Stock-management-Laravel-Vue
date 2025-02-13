<script setup>
import { defineEmits } from 'vue';

const emit = defineEmits(['search']);

const search = (e) => {
    emit('search', e.target.value);
};
</script>

<template>
    <input id="search" type="text" @input="search" placeholder="Search" class="bg-gray-100 border text-sm rounded-lg p-2.5 mx-1 my-1">
</template>