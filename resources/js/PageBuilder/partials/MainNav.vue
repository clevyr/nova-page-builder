<template>
    <a
        v-for="item in headerMenuItems"
        :target="item.target"
        :href="item.value"
        :class="getClasses(item.value)"
    >
        {{ item.name }}
    </a>
</template>

<script>
	export default {
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
			},
		},
		methods: {
			getClasses(href) {
				return `
				inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition ${(window.location.pathname === href) ? 'border-indigo-400 border-b-2' : 'border-transparent'}`;
			}
		}
	}
</script>
