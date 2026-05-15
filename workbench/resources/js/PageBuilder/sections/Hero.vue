<template>
    <div class="hero" :style="bgStyle">
        <h2>{{ content.heading }}</h2>
    </div>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps({ content: { type: Object, default: () => ({}) } });
const bgStyle = computed(() => props.content.image
    ? { backgroundImage: `url(/storage/${props.content.image})` }
    : null);
</script>

<style scoped>
.hero {
    background-color: #1f2937;
    color: white;
    padding: 3rem 2rem;
    background-size: cover;
    background-position: center;
}
.hero h2 { margin: 0; font-size: 2rem; }
</style>
