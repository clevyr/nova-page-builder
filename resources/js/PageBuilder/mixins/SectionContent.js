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
        getSection(slug, key = null) {
            if (key) {
                const section = this.content.find((s) => s.layout === slug && s.key === key);

                if (section) {
                    return section.attributes;
                }
                
                return false;
            }

            const section = this.content.filter((section) => {
                return section.layout === slug;
            });

            if (section.length > 0) {
                return section[0]['attributes'];
            }

            return false;
        },
        getSectionContent(section) {
            return section.attributes;
        },
    }
}
