/**
 * Package: Clevyr Nova Page Builder
 *
 * This mixin is passed the `content` prop from the Vue components.
 * This content is filtered to find a section with the supplied slug.
 * This content comes from the Page's "flexible content" fields
 * These fields are denoted in the page's config file.
 */
export default {
    props: ['content'],
    methods: {
        getSection(slug) {
            const section = this.content.filter((section) => {
                return section.layout === slug;
            });

            if (section.length > 0) {
                return section[0]['attributes'];
            }

            return false;
        },
    }
}
