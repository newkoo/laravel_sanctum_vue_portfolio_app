import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Preview from 'vite-plugin-vue-component-preview';
import Inspect from 'vite-plugin-inspect';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        Inspect(),
        Preview(),
        vue(),
    ],
});
