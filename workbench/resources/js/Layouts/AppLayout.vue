<template>
    <div class="workbench-app">
        <header class="workbench-header">
            <h1>{{ pageTitle }}</h1>
            <small>Workbench preview — `clevyr/nova-page-builder`</small>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const pageTitle = computed(() => page.props.page?.title ?? 'Page');
</script>

<style scoped>
.workbench-app {
    font-family: system-ui, sans-serif;
    max-width: 960px;
    margin: 0 auto;
    padding: 2rem 1rem;
}
.workbench-header {
    border-bottom: 1px solid #ddd;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
}
.workbench-header small {
    color: #6b7280;
    font-family: ui-monospace, monospace;
}
</style>
