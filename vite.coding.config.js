import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// Keep both existing entries independent and their production bundles unchanged.
export default defineConfig({
    plugins: [react()],
    base: './',
    build: {
        outDir: 'dist/coding-competition',
        emptyOutDir: true,
        cssCodeSplit: false,
        modulePreload: false,
        rollupOptions: {
            input: 'src/coding-competition.jsx',
            output: {
                format: 'es',
                entryFileNames: 'coding-competition.js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: ({ name }) => name?.endsWith('.css')
                    ? 'coding-competition.[ext]'
                    : 'assets/[name]-[hash].[ext]'
            }
        }
    }
});
