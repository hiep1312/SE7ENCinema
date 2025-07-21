import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css'],
=======
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css', 'resources/css/ticket.css', 'resources/css/scanner.css', 'resources/js/scannerQR.js'],
>>>>>>> 7e1b7c7cd4d48056b6f64d12e8382be956e05809
            refresh: true,
        }),
    ],
    css: {
        transformer: "postcss",
        postcss: './postcss.config.cjs',
    },
});
