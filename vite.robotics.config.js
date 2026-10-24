import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// Keep existing entries independent and their production bundles unchanged.
export default defineConfig({
    plugins: [react()],
    base: './',
    build: {
        outDir: 'dist/robotics-challenge',
        emptyOutDir: true,
        cssCodeSplit: false,
        modulePreload: false,
        rollupOptions: {
            input: 'src/robotics-challenge.jsx',
            output: {
                format: 'es',
                entryFileNames: 'robotics-challenge.js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: ({ name }) => name?.endsWith('.css')
                    ? 'robotics-challenge.[ext]'
                    : 'assets/[name]-[hash].[ext]'
            }
        }
    }
});
