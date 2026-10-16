import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// A separate build preserves the homepage entry and its output byte for byte.
export default defineConfig({
    plugins: [react()],
    base: './',
    build: {
        outDir: 'dist/hackathon',
        emptyOutDir: true,
        cssCodeSplit: false,
        modulePreload: false,
        rollupOptions: {
            input: 'src/hackathon.jsx',
            output: {
                format: 'es',
                entryFileNames: 'hackathon.js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: ({ name }) => name?.endsWith('.css')
                    ? 'hackathon.[ext]'
                    : 'assets/[name]-[hash].[ext]'
            }
        }
    }
});
