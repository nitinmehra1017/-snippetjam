import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [react()],
    base: './',

    build: {
        outDir: 'dist',
        emptyOutDir: true,
        cssCodeSplit: false,
        modulePreload: false,

        rollupOptions: {
            input: 'src/main.jsx',

            output: {
                format: 'es',
                entryFileNames: 'snippetjam.js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: ({ name }) => name?.endsWith('.css')
                    ? 'snippetjam.[ext]'
                    : 'assets/[name]-[hash].[ext]'
            }
        }
    }
});
