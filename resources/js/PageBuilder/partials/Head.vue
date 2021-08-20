<template>
    <inertia-head>
        <title>{{ meta.title }} | {{ meta.website }}</title>
        <meta head-key="description" name="description" :content="meta.meta_description">
        <meta head-key="keywords" name="keywords" :content="meta.meta_keywords ">
        <meta head-key="twitter:title" name="twitter:title" :content="meta.title" />
        <meta head-key="twitter:site" name="twitter:site" :content="meta.website" />
        <meta head-key="twitter:description" name="twitter:description" :content="meta.meta_description" />
        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
        <meta head-key="og:url" property="og:url" :content="websiteHost + meta.href" />
        <meta head-key="og:title" property="og:title" :content="meta.title" />
        <meta head-key="og:description" property="og:description" :content="meta.title" />
        <meta head-key="og:type" property="og:type" content="website" />
        <meta head-key="og:image" property="og:image" :content="websiteHost + meta.og_image" />
    </inertia-head>
</template>

<script>
export default {
    computed: {
        websiteHost() {
            return window.location.origin;
        },
        meta() {
            const metadata = this.$page.props.metadata;

            return {
                title: metadata.meta_title ?? 'Welcome',
                website: metadata.website ?? 'Clevyr Website',
                meta_description: metadata.meta_description ?? 'Description',
                meta_keywords: metadata.meta_keywords ?? 'Keywords',
                href: window.location.pathname,
                og_image: metadata.og_image ?? '',
            };
        },
    },
};
</script>
