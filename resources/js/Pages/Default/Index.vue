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

	export default {
		props: ['content', 'page'],
		components: {
			OneColumnLayout,
			TwoColumnLayout,
			AppLayout,
			Hero,
            Head,
		},
		methods: {
			getSection(slug) {
				const section = this.content.filter((section) => {
					return section.layout === slug;
				});

				if (section) {
					return section[0]['attributes'];
				}

				return false;
			}
		},
	}
</script>
