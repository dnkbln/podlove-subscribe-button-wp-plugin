import { defineConfig } from 'vite'
import * as path from 'path'
import vue from '@vitejs/plugin-vue'

const root = path.resolve(__dirname)

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
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
