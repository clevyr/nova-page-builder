<template>
    <app-layout>

        <Head :page="page" />

        <div v-for="section in content">
            <Hero :content="getSection(section.layout)" v-if="section.layout === 'hero'" />
            <OneColumnLayout :content="getSection(section.layout)" v-if="section.layout === 'one-column-layout'" />
            <TwoColumnLayout :content="getSection(section.layout)" v-if="section.layout === 'two-column-layout'" />
        </div>
    </app-layout>
</template>

<script>
	import AppLayout from '@/Layouts/AppLayout'
	import Hero from '@/PageBuilder/sections/Hero';
	import OneColumnLayout from "@/PageBuilder/sections/OneColumnLayout";
	import TwoColumnLayout from "@/PageBuilder/sections/TwoColumnLayout";
	import Head from '@/PageBuilder/partials/Head';
	import SectionContent from '@/PageBuilder/mixins/SectionContent';

	export default {
		props: ['content', 'page'],
        mixins: [SectionContent],
		components: {
			OneColumnLayout,
			TwoColumnLayout,
			AppLayout,
			Hero,
            Head,
		},
	}
</script>
