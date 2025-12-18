import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    // 🔴 ENG MUHIM QATOR — CSS/JS yo‘li shu bilan to‘g‘rilanadi
    base: '/build/',

    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],
            refresh: false, // production uchun
        }),
        react(),
    ],
});
