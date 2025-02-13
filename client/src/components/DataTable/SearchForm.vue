<script setup>
import { defineEmits } from 'vue';

const emit = defineEmits(['search']);

const search = (e) => {
    emit('search', e.target.value);
};
</script>

<template>
    <input id="search" type="text" @input="search" placeholder="Search">
</template>