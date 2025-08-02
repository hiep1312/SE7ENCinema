import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                'resources/css/notificationIndex.css',
                "resources/js/app.js",
                "resources/css/auth.css",
                "resources/css/ticket.css",
                "resources/css/scanner.css",
                "resources/js/scannerQR.js",
                "resources/css/userInfo.css",
                "resources/css/confirm-access.css",
                "resources/css/movieDetail.css",
                "resources/css/privacyPolicy.css",
                "resources/css/termsOfService.css",
                "resources/css/promotion.css",
                'resources/css/showtimeIndex.css',
                'resources/css/"selectfood.css"'
            ],
            refresh: true,
        }),
    ],
    css: {
        transformer: "postcss",
        postcss: "./postcss.config.cjs",
    },
});
