<template>
    <AppLayout>
        <article>
            <p v-if="!content || !content.length" class="placeholder">
                This page has no sections yet. Add some in Nova.
            </p>

            <template v-else>
                <section
                    v-for="(section, index) in content"
                    :key="index"
                    :data-layout="section.layout"
                    class="section"
                >
                    <Hero v-if="section.layout === 'hero'" :content="section.attributes" />
                    <OneColumnLayout v-else-if="section.layout === 'one-column-layout'" :content="section.attributes" />
                    <TwoColumnLayout v-else-if="section.layout === 'two-column-layout'" :content="section.attributes" />
                    <pre v-else class="unknown">Unknown layout: {{ section.layout }}</pre>
                </section>
            </template>
        </article>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Hero from '@/PageBuilder/sections/Hero.vue';
import OneColumnLayout from '@/PageBuilder/sections/OneColumnLayout.vue';
import TwoColumnLayout from '@/PageBuilder/sections/TwoColumnLayout.vue';

defineProps({
    page: { type: Object, required: true },
    content: { type: Array, default: () => [] },
});
</script>

<style scoped>
.section { margin: 2rem 0; }
.placeholder { color: #6b7280; font-style: italic; }
.unknown { background: #fee2e2; color: #991b1b; padding: 0.5rem 0.75rem; border-radius: 4px; }
</style>
