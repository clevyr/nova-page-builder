import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path';

// The workbench is rooted at vendor/orchestra/testbench-core/laravel, so Vite's
// build target needs to match — that's where the served public/ directory lives.
const skeleton = 'vendor/orchestra/testbench-core/laravel';

export default defineConfig({
    plugins: [
        laravel({
            input: ['workbench/resources/js/app.js'],
            publicDirectory: `${skeleton}/public`,
            buildDirectory: 'build',
            refresh: ['workbench/resources/views/**'],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'workbench/resources/js'),
        },
    },
    test: {
        environment: 'happy-dom',
        include: ['tests/Frontend/**/*.test.js'],
    },
});
