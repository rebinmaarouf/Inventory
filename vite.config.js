import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: [
          'bootstrap',
          'jquery',
          '@popperjs/core'
        ],
    },
    build: {
        outDir: 'public/build',
      },
    
    server: {
        proxy: {
            "/api": {
                target: "http://localhost:8000", // Replace with your backend URL
                changeOrigin: true,
                secure: false,
            },
        },
    },
});
