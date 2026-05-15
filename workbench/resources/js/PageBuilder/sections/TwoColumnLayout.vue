<template>
    <div class="two-column">
        <div class="col" v-html="content.left_col" />
        <div class="col" v-html="content.right_col" />
    </div>
</template>

<script setup>
defineProps({ content: { type: Object, default: () => ({}) } });
</script>

<style scoped>
.two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 1.5rem 0; }
</style>
