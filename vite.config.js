import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: process.env.APP_ENV === 'production' ? 'https://hw-advantage-production.up.railway.app/build/' : '/build/',
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
            detectTls: (process.env.APP_ENV === 'production') ? 'hw-advantage-production.up.railway.app' : null,
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
    build: {
        manifest: 'manifest.json',
        outDir: 'public/build',
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    },
});
