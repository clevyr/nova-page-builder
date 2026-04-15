<template>
    <app-layout>
        <div v-for="section in content" :key="section.key">
            <Hero :content="section.attributes" v-if="section.layout === 'hero'" />
            <OneColumnLayout :content="section.attributes" v-if="section.layout === 'one-column-layout'" />
            <TwoColumnLayout :content="section.attributes" v-if="section.layout === 'two-column-layout'" />
        </div>
    </app-layout>
</template>

<script>
	import AppLayout from '@/Layouts/AppLayout.vue'
	import Hero from '@/PageBuilder/sections/Hero.vue';
	import OneColumnLayout from '@/PageBuilder/sections/OneColumnLayout.vue';
	import TwoColumnLayout from '@/PageBuilder/sections/TwoColumnLayout.vue';
	import SectionContent from '@/PageBuilder/mixins/SectionContent';

	export default {
		props: ['content', 'page'],
        mixins: [SectionContent],
		components: {
			OneColumnLayout,
			TwoColumnLayout,
			AppLayout,
			Hero,
		},
	}
</script>
