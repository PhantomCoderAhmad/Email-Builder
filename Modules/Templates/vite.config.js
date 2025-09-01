import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath } from 'url';
import path from 'path';

// ✅ Correct way to get __dirname in ES Modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
    build: {
        outDir: '../../public/build-templates',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-templates',
            input: [
                path.resolve(__dirname, 'resources/assets/css/app.css'),
                path.resolve(__dirname, 'resources/assets/css/payments.css'),
                path.resolve(__dirname, 'resources/assets/js/app.js'),
                path.resolve(__dirname, 'resources/assets/js/payments.js'),
                path.resolve(__dirname, 'resources/assets/js/app.jsx'),
            ],
            refresh: true,
        }),
    ],
});
