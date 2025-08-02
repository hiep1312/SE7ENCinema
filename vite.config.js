import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css', 'resources/css/ticket.css','resources/css/privacyPolicy.css','resources/css/termsOfService.css','resources/css/client-banner.css', 'resources/css/scanner.css', 'resources/js/scannerQR.js',],
            refresh: true,
        }),
    ],
    css: {
        transformer: "postcss",
        postcss: './postcss.config.cjs',
    },
});
