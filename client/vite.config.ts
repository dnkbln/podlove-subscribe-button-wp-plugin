import { defineConfig } from 'vite'
import * as path from 'path'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

const root = path.resolve(__dirname)

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    tailwindcss()
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
      '@': path.resolve(root, 'src'),
      '@store': path.resolve(root, 'src', 'store'),
      '@sagas': path.resolve(root, 'src', 'sagas'),
      '@components': path.resolve(root, 'src', 'components'),
      '@modules': path.resolve(root, 'src', 'modules'),
      '@app-types': path.resolve(root, 'src', 'types'),
    }
  },
  build: {
    outDir: path.resolve(root, 'dist'),
    cssCodeSplit: false,
    rollupOptions: {
      output: {
        entryFileNames: `main.js`,
        chunkFileNames: `chunk-[name].js`,
        assetFileNames: `[name].[ext]`
      }
    }
  }
})
