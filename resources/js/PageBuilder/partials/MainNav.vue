<template>
    <jet-nav-link
        v-for="item in headerMenuItems"
        as="a"
        :target="item.target"
        :href="item.value"
        :active="isActive(item.value)"
    >
        {{ item.name }}
    </jet-nav-link>
</template>

<script>
	import JetNavLink from '@/Jetstream/NavLink';

	export default {
		components: {
			JetNavLink,
		},
		computed: {
			headerMenuItems() {
				if (!this.$page.props.navigations) {
					return [];
				}

				const nav = this.$page.props.navigations.find((nav) => { return nav.slug === 'header' });

				if (nav) {
					return nav.menuItems;
                }

				return null;
			}
		},
		methods: {
			isActive(href) {
				return window.location.pathname === href;
			}
		}
	}
</script>
