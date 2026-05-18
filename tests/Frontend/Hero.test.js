import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Hero from '@/PageBuilder/sections/Hero.vue';

describe('Hero section', () => {
    it('renders the heading from content props', () => {
        const wrapper = mount(Hero, {
            props: { content: { heading: 'Welcome' } },
        });

        expect(wrapper.text()).toContain('Welcome');
    });

    it('builds a background image style when content.image is provided', () => {
        const wrapper = mount(Hero, {
            props: { content: { heading: 'Hi', image: 'photo.jpg' } },
        });

        expect(wrapper.attributes('style')).toContain('background-image');
        expect(wrapper.attributes('style')).toContain('photo.jpg');
    });

    it('omits the background style when no image is provided', () => {
        const wrapper = mount(Hero, {
            props: { content: { heading: 'Hi' } },
        });

        const style = wrapper.attributes('style') ?? '';
        expect(style).not.toContain('background-image');
    });
});
