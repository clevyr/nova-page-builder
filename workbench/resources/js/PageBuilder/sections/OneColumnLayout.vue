<template>
    <div class="one-column" v-html="content.content" />
</template>

<script setup>
defineProps({ content: { type: Object, default: () => ({}) } });
</script>

<style scoped>
.one-column { padding: 1.5rem 0; }
</style>
