import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// Keep existing entries independent and their production bundles unchanged.
export default defineConfig({
    plugins: [react()],
    base: './',
    build: {
        outDir: 'dist/workshops',
        emptyOutDir: true,
        cssCodeSplit: false,
        modulePreload: false,
        rollupOptions: {
            input: 'src/workshops.jsx',
            output: {
                format: 'es',
                entryFileNames: 'workshops.js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: ({ name }) => name?.endsWith('.css')
                    ? 'workshops.[ext]'
                    : 'assets/[name]-[hash].[ext]'
            }
        }
    }
});
