import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            // vite.config.js
            input: [
                "resources/css/app.css",
                "resources/js/app.js", // <-- Ponto de entrada 1
                "resources/js/reports.js", // <-- Ponto de entrada 2
            ],
            refresh: true,
        }),
    ],
});
