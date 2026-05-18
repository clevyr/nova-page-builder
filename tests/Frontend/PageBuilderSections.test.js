import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import OneColumnLayout from '@/PageBuilder/sections/OneColumnLayout.vue';
import TwoColumnLayout from '@/PageBuilder/sections/TwoColumnLayout.vue';

describe('OneColumnLayout', () => {
    it('renders raw HTML from content.content', () => {
        const wrapper = mount(OneColumnLayout, {
            props: { content: { content: '<p>hello <strong>world</strong></p>' } },
        });

        expect(wrapper.html()).toContain('<strong>world</strong>');
    });
});

describe('TwoColumnLayout', () => {
    it('renders both columns from content.left_col and content.right_col', () => {
        const wrapper = mount(TwoColumnLayout, {
            props: {
                content: {
                    left_col: '<p>left</p>',
                    right_col: '<p>right</p>',
                },
            },
        });

        const cols = wrapper.findAll('.col');
        expect(cols).toHaveLength(2);
        expect(cols[0].html()).toContain('left');
        expect(cols[1].html()).toContain('right');
    });
});
